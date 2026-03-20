<?php
namespace App\Controllers;

use App\Models\Document;
use App\Models\Mouvement;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Contrat;
use Packages\View\MadelineView;

/**
 * Controller: Tableau de Bord Financier (Cockpit 360°)
 */
class DashboardController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userName = $_SESSION['user_name'] ?? 'Utilisateur';

        // Chargement des Mouvements de Caisse (Source de Vérité)
        $mouvements = Mouvement::fari();
        $docs = Document::fari();
        $contrats = Contrat::fari();
        $clients = Client::fari();

        $revenus = 0;
        $depenses = 0;
        $tva_a_reverser = 0;
        $brs_collecte = 0;

        foreach ($mouvements as $m) {
            if ($m->type === 'entree') {
                $revenus += ($m->montant - $m->tva_portion);
                $tva_a_reverser += $m->tva_portion;
                $brs_collecte += $m->brs_portion;
            } else {
                $depenses += $m->montant;
            }
        }

        $revenus_attente = 0;
        foreach ($docs as $d) {
            if (in_array($d->statut, ['envoye', 'valide'])) {
                $revenus_attente += $d->total_ttc;
            }
        }

        // Bilan
        $bilan = $revenus - $depenses;

        // Stats Globales
        $countClients = count($clients);
        $countProducts = count(Produit::fari());
        
        // Données Graphe (Répartition par Catégorie)
        $categories = [];
        foreach ($mouvements as $m) {
            if ($m->type === 'sortie') {
                $cat = $m->categorie;
                $categories[$cat] = ($categories[$cat] ?? 0) + $m->montant;
            }
        }

        $chartData = [
            'labels' => array_keys($categories),
            'values' => array_values($categories)
        ];

        // Évolution Chiffre d'Affaire
        $monthlyRevenues = array_fill(1, 12, 0);
        foreach ($mouvements as $m) {
            if ($m->type === 'entree') {
                $month = (int)date('m', strtotime($m->date_mouvement));
                $monthlyRevenues[$month] += ($m->montant - $m->tva_portion);
            }
        }

        // Calcul des Top Clients (Valeur Vie / LTV)
        $clientMap = [];
        foreach ($clients as $c) { $clientMap[$c->id] = $c->nom; }

        $clientRevenue = [];
        foreach ($docs as $d) {
            if (in_array($d->statut, ['paye', 'valide'])) {
                $cid = $d->client_id;
                if (!isset($clientRevenue[$cid])) {
                    $clientRevenue[$cid] = ['name' => $clientMap[$cid] ?? 'Client Inconnu', 'value' => 0];
                }
                $clientRevenue[$cid]['value'] += $d->total_ttc;
            }
        }
        uasort($clientRevenue, function($a, $b) { return $b['value'] <=> $a['value']; });
        $top5Clients = array_slice($clientRevenue, 0, 5);

        return MadelineView::render('auth/dashboard', [
            'name' => $userName,
            'revenus' => $revenus,
            'revenus_attente' => $revenus_attente,
            'depenses' => $depenses,
            'bilan' => $bilan,
            'tva_a_reverser' => $tva_a_reverser,
            'brs_collecte' => $brs_collecte,
            'countClients' => $countClients,
            'countProducts' => $countProducts,
            'chartData' => $chartData,
            'revenueEvolution' => [
                'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                'values' => array_values($monthlyRevenues)
            ],
            'recentDocs' => array_slice($docs, 0, 5),
            'topClients' => $top5Clients
        ]);
    }
}