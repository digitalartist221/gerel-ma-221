<?php
namespace App\Controllers;

use App\Models\Mouvement;
use App\Models\Entreprise;
use Packages\View\MadelineView;
use Packages\Http\Request;

/**
 * Controller: Journal de Caisse & Fiscalité
 */
class CaisseController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $eid = $_SESSION['active_entreprise_id'] ?? 'all';
        $params = $eid !== 'all' ? ['entreprise_id' => $eid] : [];
        
        $mouvements = Mouvement::fari($params, "ORDER BY date_mouvement DESC, id DESC");
        
        // Calcul des totaux
        $totalEntrees = 0;
        $totalSorties = 0;
        foreach ($mouvements as $m) {
            if ($m->type === 'entree') $totalEntrees += $m->montant;
            else $totalSorties += $m->montant;
        }

        return MadelineView::render('caisse/index', [
            'mouvements' => $mouvements,
            'balance' => $totalEntrees - $totalSorties,
            'totalEntrees' => $totalEntrees,
            'totalSorties' => $totalSorties
        ]);
    }

    public function amul() {
        $entreprises = Entreprise::fari();
        return MadelineView::render('caisse/edit', [
            'mouvement' => null,
            'entreprises' => $entreprises
        ]);
    }

    public function bindu() {
        $req = new Request();
        $id = $req->input('id');

        // Validation front-end via server
        if (empty($req->input('libelle')) || empty($req->input('montant')) || empty($req->input('entreprise_id'))) {
            $_SESSION['error'] = "Veuillez remplir le libellé, le montant et l'entité gestionnaire.";
            header("Location: " . ($id ? "/caisse/edit/{$id}" : "/caisse/nouveau"));
            exit;
        }

        $data = [
            'type' => $req->input('type', 'sortie'),
            'libelle' => $req->input('libelle'),
            'montant' => (float) $req->input('montant'),
            'date_mouvement' => $req->input('date_mouvement', date('Y-m-d')),
            'categorie' => $req->input('categorie', 'Autre'),
            'entreprise_id' => $req->input('entreprise_id'),
            'user_id' => $_SESSION['user_id'] ?? 1,
            'tva_portion' => (float) $req->input('tva_portion', 0),
            'brs_portion' => (float) $req->input('brs_portion', 0)
        ];

        if ($id) {
            Mouvement::weccit($data, ['id' => $id]);
            $_SESSION['success'] = "Mouvement mis à jour.";
        } else {
            Mouvement::bindu($data);
            $_SESSION['success'] = "Nouveau mouvement enregistré en Caisse !";

            // Alerte de sécurité : Sortie importante
            if ($data['type'] === 'sortie' && $data['montant'] >= 1000000) {
                $adminEmail = \Core\Config::get('app.admin_email', 'admin@maye.com');
                $body = "<h2>Alerte Flux de Caisse</h2><br>Une sortie de caisse importante de <b>" . number_format($data['montant'], 0) . " XOF</b> a été enregistrée.<br>Libellé : {$data['libelle']}.";
                \Packages\Mail\Mail::to($adminEmail, "ALERTE : Sortie de caisse > 1M", $body);
            }
        }

        header("Location: /caisse");
        exit;
    }

    public function report() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $eid = $_SESSION['active_entreprise_id'] ?? 'all';
        $params = $eid !== 'all' ? ['entreprise_id' => $eid] : [];
        
        $mouvements = Mouvement::fari($params);
        
        $stats = [
            'tva_collectee' => 0,
            'brs_collecte' => 0,
            'ventes_nettes' => 0,
            'charges_totales' => 0,
            'categories' => []
        ];

        foreach ($mouvements as $m) {
            if ($m->type === 'entree') {
                $stats['tva_collectee'] += $m->tva_portion;
                $stats['brs_collecte'] += $m->brs_portion;
                $stats['ventes_nettes'] += ($m->montant - $m->tva_portion);
            } else {
                $stats['charges_totales'] += $m->montant;
                $cat = $m->categorie;
                $stats['categories'][$cat] = ($stats['categories'][$cat] ?? 0) + $m->montant;
            }
        }

        return MadelineView::render('caisse/report', [
            'stats' => $stats
        ]);
    }

    public function delete($id) {
        Mouvement::far(['id' => $id]);
        $_SESSION['success'] = "Opération supprimée du journal.";
        header("Location: /caisse");
        exit;
    }
}
