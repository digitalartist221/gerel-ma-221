<?php
namespace App\Controllers;

use App\Models\Depense;
use App\Models\Entreprise;
use Packages\View\MadelineView;
use Packages\Http\Request;

/**
 * Controller: Dépenses (Gestion des flux de sortie)
 */
class DepenseController {
    public function index() {
        $depenses = Depense::fari();
        return MadelineView::render('depense/index', [
            'depenses' => $depenses
        ]);
    }

    public function amul() {
        $entreprises = Entreprise::fari();
        return MadelineView::render('depense/edit', [
            'depense' => null,
            'entreprises' => $entreprises
        ]);
    }

    public function edit($id) {
        $results = Depense::fari(['id' => $id]);
        $entreprises = Entreprise::fari();
        return MadelineView::render('depense/edit', [
            'depense' => $results[0] ?? null,
            'entreprises' => $entreprises
        ]);
    }

    public function bindu() {
        $req = new Request();
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $data = [
            'titre' => $req->input('titre'),
            'montant' => $req->input('montant'),
            'categorie' => $req->input('categorie', 'autre'),
            'date_depense' => $req->input('date_depense', date('Y-m-d')),
            'entreprise_id' => $req->input('entreprise_id'),
            'user_id' => $_SESSION['user_id']
        ];

        $id = $req->input('id');
        if ($id) {
            Depense::weccit($data, ['id' => $id]);
        } else {
            Depense::bindu($data);
        }

        header("Location: /depenses");
        exit;
    }
}
