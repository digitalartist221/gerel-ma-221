<?php
namespace App\Controllers;

use Packages\Paytech\Paytech;
use App\Models\Document;
use App\Models\Mouvement;
use Packages\Http\Request;

class PaytechController {
    public function init($doc_id) {
        $paytech = new Paytech();
        
        // Simulation document
        $response = $paytech->send(
            "Facture #$doc_id", 
            5000, 
            $doc_id, 
            "MDL-$doc_id"
        );

        if (isset($response['success']) && $response['success'] == 1) {
            header("Location: " . $response['redirect_url']);
            exit;
        }

        return "Erreur Paytech : " . ($response['error'] ?? 'Inconnue');
    }

    public function success() {
        $req = new Request();
        $docId = $req->input('item_id'); // Paytech renvoie item_id
        
        if ($docId) {
            $doc = Document::fari(['id' => $docId])[0] ?? null;
            if ($doc && $doc->statut !== 'paye') {
                Document::weccit(['statut' => 'paye'], ['id' => $docId]);
                
                // On délègue l'enregistrement en caisse via la méthode existante
                // Note: recordPayment devrait idéalement être statique ou dans un Service
                $docCtrl = new DocumentController();
                // Utilisation d'une astuce pour appeler une méthode privée pour le test
                // ou simplement réimplémenter la logique ici pour la rapidité.
                $this->triggerFinancialRecord($doc);
            }
        }

        header("Location: /business/success");
        exit;
    }

    private function triggerFinancialRecord($doc) {
        $exists = Mouvement::fari(['document_id' => $doc->id]);
        if (!empty($exists)) return;

        Mouvement::bindu([
            'type' => 'entree',
            'libelle' => "Paiement Paytech - {$doc->type} N° {$doc->numero}",
            'montant' => $doc->total_ttc,
            'date_mouvement' => date('Y-m-d'),
            'categorie' => 'Vente',
            'document_id' => $doc->id,
            'entreprise_id' => $doc->entreprise_id,
            'user_id' => 1,
            'tva_portion' => $doc->tva_amount,
            'brs_amount' => $doc->brs_amount
        ]);
    }

    public function cancel() {
        return "Paiement annulé.";
    }
}
