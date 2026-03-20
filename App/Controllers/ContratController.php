<?php
namespace App\Controllers;

use App\Models\Contrat;
use App\Models\Client;
use App\Models\Entreprise;
use App\Models\Produit;
use Packages\View\MadelineView;
use Packages\Mail\Mail;
use Packages\Http\Request;

/**
 * Controller: Contrats (Articles & Prestations)
 */
class ContratController {
    public function index() {
        $contrats = Contrat::fari();
        return MadelineView::render('contrat/index', [
            'contrats' => $contrats
        ]);
    }

    public function create() {
        $clients = Client::fari();
        $entreprises = Entreprise::fari();
        $articles = \App\Models\ArticleJuridique::fari();
        return MadelineView::render('contrat/edit', [
            'contrat' => null,
            'clients' => $clients,
            'entreprises' => $entreprises,
            'articles' => $articles
        ]);
    }

    public function edit($id) {
        $results = Contrat::fari(['id' => $id]);
        $clients = Client::fari();
        $entreprises = Entreprise::fari();
        $articles = \App\Models\ArticleJuridique::fari();
        return MadelineView::render('contrat/edit', [
            'contrat' => $results[0] ?? null,
            'clients' => $clients,
            'entreprises' => $entreprises,
            'articles' => $articles
        ]);
    }

    public function bindu() {
        $req = new Request();
        $id = $req->input('id');

        $lines = json_decode($req->input('lines_json', '[]'), true);
        
        $data = [
            'numero' => $req->input('numero', 'CTR-' . time()),
            'client_id' => $req->input('client_id'),
            'entreprise_id' => $req->input('entreprise_id'),
            'total_ht' => 0, // Contrat purement juridique
            'total_ttc' => 0,
            'statut' => $req->input('statut', 'brouillon'),
            'token_public' => bin2hex(random_bytes(16)),
            'date_signature' => date('Y-m-d'),
            'contenu_json' => json_encode($lines),
            'notes' => $req->input('notes', ''),
            'signature_hash' => $req->input('signature_base64', '')
        ];

        if ($id) {
            unset($data['token_public']);
            $old = Contrat::fari(['id' => $id])[0] ?? null;
            if ($old && $old->statut !== 'valide' && $data['statut'] === 'valide') {
                $this->sendEmail($id, true);
                $_SESSION['success'] = "Contrat validé et sécurisé ! Client notifié.";
            } else {
                $_SESSION['success'] = "Contrat mis à jour avec succès.";
            }
            Contrat::weccit($data, ['id' => $id]);
        } else {
            $newId = Contrat::bindu($data)->id;
            if ($data['statut'] === 'valide') {
                $this->sendEmail($newId, true);
                $_SESSION['success'] = "Contrat créé et validé !";
            } else {
                $_SESSION['success'] = "Brouillon de contrat enregistré.";
            }
        }

        // Auto-Enregistrement des articles juridiques
        foreach ($lines as $line) {
            if (!empty($line['designation'])) {
                $exists = \App\Models\ArticleJuridique::fari(['nom' => $line['designation']]);
                if (empty($exists)) {
                    \App\Models\ArticleJuridique::bindu([
                        'numero' => 'Art-' . rand(100, 999),
                        'nom' => $line['designation'],
                        'description' => $line['designation'],
                        'entreprise_id' => $data['entreprise_id']
                    ]);
                }
            }
        }

        header("Location: /contrats");
        exit;
    }

    public function publicView($token) {
        $results = Contrat::fari(['token_public' => $token]);
        $contrat = $results[0] ?? null;

        if (!$contrat) return "Contrat introuvable.";

        if (!$contrat->is_read) {
            Contrat::weccit(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')], ['id' => $contrat->id]);
        }

        return MadelineView::render('business/contrat_view', [
            'contrat' => $contrat
        ]);
    }

    public function publicAction($token) {
        $req = new Request();
        $results = Contrat::fari(['token_public' => $token]);
        $contrat = $results[0] ?? null;
        if (!$contrat) return "Contrat introuvable.";

        $action = $req->input('action');
        if ($action === 'accept') {
            $name = $req->input('signed_by');
            Contrat::weccit([
                'statut' => 'signe',
                'signed_by' => $name,
                'signed_at' => date('Y-m-d H:i:s'),
                'signature_hash' => hash('sha256', $token . $name . time())
            ], ['id' => $contrat->id]);
        } elseif ($action === 'refuse') {
            Contrat::weccit(['statut' => 'annule'], ['id' => $contrat->id]);
        }

        header("Location: /view/contrat/" . $token);
        exit;
    }

    public function sendEmail($id, $silent = false) {
        $results = Contrat::fari(['id' => $id]);
        $contrat = $results[0] ?? null;
        if (!$contrat) return "Contrat introuvable.";

        $client = Client::fari(['id' => $contrat->client_id])[0] ?? null;
        if (!$client || !$client->email) return "Email du client introuvable.";

        $url = "http://localhost:8000/view/contrat/" . $contrat->token_public;
        
        $body = "Bonjour {$client->nom},<br><br>Vous avez reçu un nouveau contrat à signer : <b>{$contrat->numero}</b>.<br>";
        $body .= "D'un montant de " . number_format($contrat->total_ttc, 0) . " XOF.<br><br>";
        $body .= "<a href='{$url}' style='background:#050510; color:white; padding:15px 30px; text-decoration:none; border-radius:50px;'>Consulter et signer le contrat</a>";

        Mail::to($client->email, "Contrat Gerel Ma - " . $contrat->numero, $body);

        Contrat::weccit([
            'statut' => 'envoye',
            'sent_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);

        if ($silent) return true;
        header("Location: /contrats/edit/" . $id . "?sent=1");
        exit;
    }

    public function print($id) {
        $results = Contrat::fari(['id' => $id]);
        $contrat = $results[0] ?? null;
        if (!$contrat) return "Introuvable.";
        $client = Client::fari(['id' => $contrat->client_id])[0] ?? null;
        $entreprise = Entreprise::fari(['id' => $contrat->entreprise_id])[0] ?? null;
        return MadelineView::render('contrat/print', ['contrat' => $contrat, 'client' => $client, 'entreprise' => $entreprise], true);
    }
}