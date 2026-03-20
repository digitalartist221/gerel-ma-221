<?php
namespace App\Controllers;

use App\Models\Document;
use App\Models\Client;
use App\Models\Entreprise;
use Packages\View\MadelineView;
use Packages\Mail\Mail;
use Packages\Http\Request;

/**
 * Controller: Documents (Factures, BC, BL)
 */
class DocumentController {
    public function index() {
        $documents = Document::fari();
        return MadelineView::render('document/index', [
            'documents' => $documents
        ]);
    }

    public function amul() {
        $clients = Client::fari();
        $entreprises = Entreprise::fari();
        $produits = \App\Models\Produit::fari();
        
        // Auto-select if unique
        $defaultEnterpriseId = (count($entreprises) === 1) ? $entreprises[0]->id : null;

        return MadelineView::render('document/edit', [
            'document' => null,
            'clients' => $clients,
            'entreprises' => $entreprises,
            'produits' => $produits,
            'default_entreprise_id' => $defaultEnterpriseId
        ]);
    }

    public function edit($id) {
        $results = Document::fari(['id' => $id]);
        $clients = Client::fari();
        $entreprises = Entreprise::fari();
        $produits = \App\Models\Produit::fari();
        return MadelineView::render('document/edit', [
            'document' => $results[0] ?? null,
            'clients' => $clients,
            'entreprises' => $entreprises,
            'produits' => $produits
        ]);
    }

    public function bindu() {
        $req = new Request();
        $id = $req->input('id');

        // Récupération des lignes structurées (envoyées via JS)
        $lines = json_decode($req->input('lines_json', '[]'), true);
        
        $totalHt = 0;
        $totalTva = 0;
        $totalBrs = 0;
        $taxEnabled = $req->input('tax_enabled') == '1';
        $taxType = $req->input('tax_type', 'TVA'); // TVA, BRS, VRS

        foreach($lines as $line) {
            $ht = ($line['prix'] ?? 0) * ($line['qty'] ?? 0);
            $totalHt += $ht;
            if ($taxEnabled) {
                if ($taxType === 'TVA') {
                    $totalTva += $ht * 0.18;
                } elseif ($taxType === 'BRS') {
                    $totalBrs += $ht * 0.05;
                } elseif ($taxType === 'VRS') {
                    // VRS is also a withholding, usually calculated on HT
                    $totalBrs += $ht * 0.05; 
                }
            }
        }

        // TTC = HT + TVA - Withholdings (BRS/VRS)
        $totalTtc = $totalHt + $totalTva - $totalBrs;

        $data = [
            'type' => $req->input('type', 'Facture'),
            'numero' => $req->input('numero', 'DOC-' . time()),
            'client_id' => $req->input('client_id'),
            'entreprise_id' => $req->input('entreprise_id'),
            'total_ht' => $totalHt,
            'tva_amount' => $totalTva,
            'brs_amount' => $totalBrs,
            'total_ttc' => $totalTtc,
            'tax_enabled' => $taxEnabled ? 1 : 0,
            'statut' => $req->input('statut', 'brouillon'),
            'token_public' => bin2hex(random_bytes(16)),
            'date_emission' => date('Y-m-d'),
            'contenu_json' => json_encode($lines)
        ];

        if ($id) {
            unset($data['token_public']);
            $oldDoc = Document::fari(['id' => $id])[0] ?? null;
            
            // Only trigger actions if entering a specific status for the first time
            if (($data['statut'] === 'valide' || $data['statut'] === 'signe') && ($oldDoc && !in_array($oldDoc->statut, ['valide', 'paye', 'signe']))) {
                $this->handleStockReduction($lines, $id);
                $this->sendEmail($id, true);
            }

            if ($data['statut'] === 'paye' && ($oldDoc && $oldDoc->statut !== 'paye')) {
                $this->recordPayment($id);
                $_SESSION['success'] = "Paiement encaissé ! Trésorerie mise à jour.";
            } elseif ($data['statut'] === 'valide' && ($oldDoc && !in_array($oldDoc->statut, ['valide', 'paye', 'signe']))) {
                $_SESSION['success'] = "Document validé ! Stock mis à jour.";
            } else {
                $_SESSION['success'] = "Document mis à jour avec succès.";
            }
            
            Document::weccit($data, ['id' => $id]);
        } else {
            // Support du parent_id à la création (ex: via transform)
            $data['parent_id'] = $req->input('parent_id') ?? $data['parent_id'] ?? null;
            $newDoc = Document::bindu($data);
            $newId = $newDoc->id;
            $_SESSION['success'] = "Nouveau document créé (Brouillon).";
            if ($data['statut'] === 'valide' || $data['statut'] === 'signe') {
                $this->handleStockReduction($lines, $newId);
                $this->sendEmail($newId, true);
                if ($data['statut'] === 'paye') $this->recordPayment($newId);
            }
        }

        // Auto-Catalogage : Sauvegarder les nouveaux produits détectés dans les lignes
        foreach ($lines as $line) {
            if (empty($line['product_id']) && !empty($line['designation']) && ($line['prix'] ?? 0) > 0) {
                // Vérifier si le produit existe déjà par son nom pour éviter les doublons
                $exists = \App\Models\Produit::fari(['designation' => $line['designation']]);
                if (empty($exists)) {
                    \App\Models\Produit::bindu([
                        'designation' => $line['designation'],
                        'prix_unitaire' => $line['prix'],
                        'tva' => $line['tva'] ?? 18,
                        'stock' => $line['qty'] ?? 0,
                        'entreprise_id' => $data['entreprise_id']
                    ]);
                }
            }
        }

        header("Location: /documents");
        exit;
    }

    public function publicView($type, $token) {
        $results = Document::fari(['token_public' => $token, 'type' => ucfirst($type)]);
        $doc = $results[0] ?? null;

        if (!$doc) return "Document introuvable.";

        // Tracking: Marquage comme lu
        if (!$doc->is_read) {
            Document::weccit([
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s')
            ], ['id' => $doc->id]);
        }

        $entreprise = Entreprise::fari(['id' => $doc->entreprise_id])[0] ?? null;

        return MadelineView::render('business/document_view', [
            'doc' => $doc,
            'token' => $token,
            'entreprise' => $entreprise
        ]);
    }

    public function publicAction($type, $token) {
        $req = new Request();
        $action = $req->input('action');
        $name = $req->input('signed_by');
        
        if ($action === 'accept') {
            $results = Document::fari(['token_public' => $token]);
            $doc = $results[0] ?? null;
            if ($doc) {
                Document::weccit([
                    'statut' => 'signe',
                    'signed_at' => date('Y-m-d H:i:s'),
                    'signed_by' => $name,
                    'signature_hash' => hash('sha256', $token . $name . time())
                ], ['id' => $doc->id]);
            }
        } else {
            Document::weccit(['statut' => 'refuse'], ['token_public' => $token]);
        }
        
        return MadelineView::render('business/document_success', ['action' => $action]);
    }

    public function transform($id, $toType) {
        $results = Document::fari(['id' => $id]);
        $source = $results[0] ?? null;
        if (!$source) return "Source introuvable.";

        $newType = match($toType) {
            'ndoggal' => 'Ndoggal (BC)',
            'faywi'   => 'Fay-wi (Facture)',
            'livraison' => 'Bon de livraison',
            default => ucfirst($toType)
        };

        if ($source->type === 'Cee-mi (Devis)' && $toType === 'ndoggal') {
            $newType = 'Ndoggal (BC)';
        }

        $data = [
            'type' => $newType,
            'numero' => 'TR-' . strtoupper(substr($toType, 0, 2)) . '-' . time(),
            'client_id' => $source->client_id,
            'entreprise_id' => $source->entreprise_id,
            'parent_id' => $source->id,
            'total_ht' => $source->total_ht,
            'tva_amount' => $source->tva_amount,
            'brs_amount' => $source->brs_amount,
            'total_ttc' => $source->total_ttc,
            'tax_enabled' => $source->tax_enabled,
            'statut' => 'brouillon',
            'token_public' => bin2hex(random_bytes(16)),
            'date_emission' => date('Y-m-d'),
            'contenu_json' => $source->contenu_json
        ];

        // Marquer le document source comme "Payé/Terminé" pour éviter les doublons visuels
        // et indiquer qu'il a été traité.
        Document::weccit(['statut' => 'paye'], ['id' => $source->id]);

        Document::bindu($data);
        header("Location: /documents");
        exit;
    }

    public function sendEmail($id, $silent = false) {
        $results = Document::fari(['id' => $id]);
        $doc = $results[0] ?? null;
        if (!$doc) return "Document introuvable.";

        $client = Client::fari(['id' => $doc->client_id])[0] ?? null;
        if (!$client || !$client->email) return "Email du client introuvable.";

        $entreprise = Entreprise::fari(['id' => $doc->entreprise_id])[0] ?? null;
        $fromName = $entreprise ? $entreprise->nom : "Gerel Ma Business";

        $url = "http://localhost:8000/view/" . strtolower($doc->type) . "/" . $doc->token_public;
        
        $body = "Bonjour {$client->nom},<br><br>Vous avez reçu un nouveau document de la part de <b>{$fromName}</b> : <b>{$doc->type} N° {$doc->numero}</b>.<br>";
        $body .= "D'un montant de " . number_format($doc->total_ttc, 0) . " XOF.<br><br>";
        $body .= "<a href='{$url}' style='background:#050510; color:white; padding:15px 30px; text-decoration:none; border-radius:50px; font-weight:bold; display:inline-block;'>Consulter le document</a>";

        Mail::to($client->email, "[{$fromName}] Votre Document " . $doc->numero, $body);

        // Tracking: Mise à jour statut et date d'envoi
        Document::weccit([
            'statut' => 'envoye',
            'sent_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);

        if ($silent) return true;

        header("Location: /documents/edit/" . $id . "?sent=1");
        exit;
    }

    public function print($id) {
        $results = Document::fari(['id' => $id]);
        $doc = $results[0] ?? null;
        if (!$doc) return "Introuvable.";
        
        $client = Client::fari(['id' => $doc->client_id])[0] ?? null;
        $entreprise = Entreprise::fari(['id' => $doc->entreprise_id])[0] ?? null;

        return MadelineView::render('document/print', [
            'doc' => $doc,
            'client' => $client,
            'entreprise' => $entreprise
        ], true); // Raw mode for printing
    }

    private function recordPayment($id) {
        $results = Document::fari(['id' => $id]);
        $doc = $results[0] ?? null;
        if (!$doc) return;

        // Éviter les doublons
        $exists = \App\Models\Mouvement::fari(['document_id' => $doc->id]);
        if (!empty($exists)) return;

        \App\Models\Mouvement::bindu([
            'type' => 'entree',
            'libelle' => "Paiement - {$doc->type} N° {$doc->numero}",
            'montant' => $doc->total_ttc,
            'date_mouvement' => date('Y-m-d'),
            'categorie' => 'Vente',
            'document_id' => $doc->id,
            'entreprise_id' => $doc->entreprise_id,
            'user_id' => $_SESSION['user_id'] ?? 1,
            'tva_portion' => $doc->tva_amount,
            'brs_amount' => $doc->brs_amount
        ]);
    }

    private function handleStockReduction(array $lines, $docId = null) {
        // Sécurité : Si ce document vient d'un parent qui a déjà été validé, on ne réduit pas deux fois.
        if ($docId) {
            $current = Document::fari(['id' => $docId])[0] ?? null;
            
            // Si le document est déjà marqué comme ayant eu une réduction de stock (logique métier)
            // Ou s'il a un parent déjà traité
            if ($current && $current->stock_reduced) return; 

            if ($current && $current->parent_id) {
                $parent = Document::fari(['id' => $current->parent_id])[0] ?? null;
                if ($parent && in_array($parent->statut, ['valide', 'paye', 'signe'])) {
                    return;
                }
            }
        }

        foreach ($lines as $line) {
            if (!empty($line['product_id'])) {
                $product = \App\Models\Produit::fari(['id' => $line['product_id']])[0] ?? null;
                if ($product) {
                    $newStock = $product->stock - ($line['qty'] ?? 0);
                    \App\Models\Produit::weccit(['stock' => $newStock], ['id' => $product->id]);
                }
            }
        }

        // Marquer le document comme traité
        if ($docId) {
            Document::weccit(['stock_reduced' => 1], ['id' => $docId]);
        }
    }
}