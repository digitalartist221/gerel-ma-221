<?php
namespace App\Controllers;

use Core\Router;
use Packages\View\MadelineView;
use App\Models\Client;

use Packages\Http\Request;

/**
 * Controller: Clients (CRM)
 * Design: Zen-Industrial
 */
class ClientController {
    public function index() {
        $clients = Client::fari();
        return MadelineView::render('client/index', [
            'clients' => $clients
        ]);
    }

    public function amul() {
        return MadelineView::render('client/edit', [
            'client' => null
        ]);
    }

    public function edit($id) {
        $results = Client::fari(['id' => $id]);
        return MadelineView::render('client/edit', [
            'client' => $results[0] ?? null
        ]);
    }

    public function view($id) {
        $results = Client::fari(['id' => $id]);
        $client = $results[0] ?? null;
        
        if (!$client) {
            header("Location: /clients");
            exit;
        }

        // Récupérer l'historique des documents pour ce client
        $documents = \App\Models\Document::fari(['client_id' => $id]);

        $totalSpent = 0;
        $outstandingBalance = 0;

        foreach ($documents as $doc) {
            if ($doc->statut === 'paye') {
                $totalSpent += $doc->total_ttc;
            } elseif (in_array($doc->statut, ['valide', 'envoye'])) {
                $outstandingBalance += $doc->total_ttc;
            }
        }

        return MadelineView::render('client/view', [
            'client' => $client,
            'documents' => $documents,
            'totalSpent' => $totalSpent,
            'outstandingBalance' => $outstandingBalance
        ]);
    }

    public function bindu() {
        $req = new Request();
        $id = $req->input('id');
        
        $data = [
            'nom' => $req->input('nom'),
            'email' => $req->input('email'),
            'telephone' => $req->input('telephone'),
            'adresse' => $req->input('adresse')
        ];

        if ($id) {
            Client::weccit($data, ['id' => $id]);
            $_SESSION['success'] = "Coordonnées du client mises à jour.";
        } else {
            Client::bindu($data);
            $_SESSION['success'] = "Nouveau partenaire ajouté au CRM !";
        }

        header("Location: /clients");
        exit;
    }
}