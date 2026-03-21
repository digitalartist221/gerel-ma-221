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
        if (session_status() === PHP_SESSION_NONE) session_start();
        $eid = $_SESSION['active_entreprise_id'] ?? 'all';
        $params = $eid !== 'all' ? ['entreprise_id' => $eid] : [];
        $clients = Client::fari($params, 'id DESC');
        return MadelineView::render('client/index', [
            'clients' => $clients
        ]);
    }

    public function amul() {
        $entreprises = \App\Models\Entreprise::fari();
        return MadelineView::render('client/edit', [
            'client' => null,
            'entreprises' => $entreprises
        ]);
    }

    public function edit($id) {
        $results = Client::fari(['id' => $id]);
        $entreprises = \App\Models\Entreprise::fari();
        return MadelineView::render('client/edit', [
            'client' => $results[0] ?? null,
            'entreprises' => $entreprises
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

        // Validation front-end via server
        if (empty($req->input('nom')) || empty($req->input('email')) || empty($req->input('entreprise_id'))) {
            $_SESSION['error'] = "Veuillez insérer les champs obligatoires (Nom, Email, Entité).";
            header("Location: " . ($id ? "/clients/edit/{$id}" : "/clients/nouveau"));
            exit;
        }
        
        $data = [
            'nom' => $req->input('nom'),
            'email' => $req->input('email'),
            'telephone' => $req->input('telephone'),
            'adresse' => $req->input('adresse'),
            'ninea' => $req->input('ninea'),
            'rc' => $req->input('rc'),
            'entreprise_id' => $req->input('entreprise_id')
        ];

        if ($id) {
            Client::weccit($data, ['id' => $id]);
            $_SESSION['success'] = "Coordonnées du client mises à jour.";
        } else {
            Client::bindu($data);
            $_SESSION['success'] = "Nouveau partenaire ajouté au CRM !";
            
            // Notification Admin
            $adminEmail = \Core\Config::get('app.admin_email', 'admin@maye.com');
            \Packages\Mail\Mail::to($adminEmail, "NOUVEAU CLIENT : {$data['nom']}", "Un nouveau partenaire a été ajouté au CRM : <b>{$data['nom']}</b> ({$data['email']}).");
        }

        header("Location: /clients");
        exit;
    }
}