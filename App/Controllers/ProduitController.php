<?php
namespace App\Controllers;

use Packages\View\MadelineView;
use App\Models\Produit;
use App\Models\Entreprise;
use Packages\Http\Request;

/**
 * Controller: Produits
 * Design: Zen-Industrial
 */
class ProduitController {
    public function index() {
        $produits = Produit::fari();
        return MadelineView::render('produit/index', [
            'produits' => $produits
        ]);
    }

    public function amul() {
        $entreprises = Entreprise::fari();
        return MadelineView::render('produit/edit', [
            'produit' => null,
            'entreprises' => $entreprises
        ]);
    }

    public function edit($id) {
        $results = Produit::fari(['id' => $id]);
        $entreprises = Entreprise::fari();
        return MadelineView::render('produit/edit', [
            'produit' => $results[0] ?? null,
            'entreprises' => $entreprises
        ]);
    }

    public function bindu() {
        $req = new Request();
        $id = $req->input('id');
        
        $data = [
            'designation' => $req->input('designation'),
            'entreprise_id' => $req->input('entreprise_id'),
            'prix_unitaire' => (float)($req->input('prix_unitaire') ?? 0),
            'tva' => (float)($req->input('tva') ?? 18),
            'stock' => (int)($req->input('stock') ?? 0)
        ];

        if ($id) {
            Produit::weccit($data, ['id' => $id]);
            $_SESSION['success'] = "Produit mis à jour dans le catalogue.";
        } else {
            Produit::bindu($data);
            $_SESSION['success'] = "Nouvel item ajouté à l'inventaire !";
        }

        header("Location: /produits");
        exit;
    }
}