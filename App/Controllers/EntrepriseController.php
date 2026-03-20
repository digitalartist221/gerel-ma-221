<?php
namespace App\Controllers;

use Packages\View\MadelineView;
use App\Models\Entreprise;
use Packages\Http\Request;

/**
 * Controller: Entreprises
 */
class EntrepriseController {
    public function index() {
        $entreprises = Entreprise::fari();
        return MadelineView::render('entreprise/index', [
            'entreprises' => $entreprises
        ]);
    }

    public function amul() {
        return MadelineView::render('entreprise/edit', [
            'entreprise' => null
        ]);
    }

    public function edit($id) {
        $results = Entreprise::fari(['id' => $id]);
        return MadelineView::render('entreprise/edit', [
            'entreprise' => $results[0] ?? null
        ]);
    }

    public function bindu() {
        $req = new Request();
        $id = $req->input('id');

        $data = [
            'nom' => $req->input('nom'),
            'siret' => $req->input('siret'),
            'adresse' => $req->input('adresse'),
            'ville' => $req->input('ville'),
            'logo' => $req->input('logo'),
            'ninea' => $req->input('ninea'),
            'rc' => $req->input('rc'),
            'website' => $req->input('website'),
            'email' => $req->input('email'),
            'contact' => $req->input('contact')
        ];

        if ($id) {
            Entreprise::weccit($data, ['id' => $id]);
            $_SESSION['success'] = "Identité de l'entreprise mise à jour !";
        } else {
            Entreprise::bindu($data);
            $_SESSION['success'] = "Nouvelle entreprise configurée !";
        }

        header("Location: /entreprises");
        exit;
    }
}