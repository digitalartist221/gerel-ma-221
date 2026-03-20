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

        // 1. Définition des filtres dynamiques (Période & Entreprise)
        $period = $_GET['period'] ?? 'this_month';
        $startDate = '1970-01-01';
        $endDate = '2099-12-31';

        if ($period === 'this_month') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        } elseif ($period === 'last_month') {
            $startDate = date('Y-m-01', strtotime('first day of last month'));
            $endDate = date('Y-m-t', strtotime('last day of last month'));
        } elseif ($period === 'this_year') {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        } elseif ($period === 'custom') {
            $startDate = $_GET['start'] ?? $startDate;
            $endDate = $_GET['end'] ?? $endDate;
        }

        $entrepriseId = $_GET['entreprise_id'] ?? 'all';
        $db = Mouvement::getConnection();

        // 2. Fetch filtré des Mouvements de Caisse
        $mouvementsQuery = "SELECT * FROM mouvements WHERE date_mouvement BETWEEN :start AND :end";
        $mouvementsParams = ['start' => $startDate, 'end' => $endDate];
        if ($entrepriseId !== 'all') {
            $mouvementsQuery .= " AND entreprise_id = :eid";
            $mouvementsParams['eid'] = $entrepriseId;
        }
        $stmtMouv = $db->prepare($mouvementsQuery);
        $stmtMouv->execute($mouvementsParams);
        $mouvements = $stmtMouv->fetchAll(\PDO::FETCH_OBJ);

        // Fetch filtré des Documents (Factures/Devis)
        $docQuery = "SELECT * FROM documents WHERE date_doc BETWEEN :start AND :end";
        $docParams = ['start' => $startDate, 'end' => $endDate];
        if ($entrepriseId !== 'all') {
            $docQuery .= " AND entreprise_id = :eid";
            $docParams['eid'] = $entrepriseId;
        }
        $stmtDoc = $db->prepare($docQuery);
        $stmtDoc->execute($docParams);
        $docs = $stmtDoc->fetchAll(\PDO::FETCH_OBJ);

        // Autres entités (non filtrées temporellement pour les stats statiques)
        $clients = Client::fari();
        $entreprises = \App\Models\Entreprise::fari();

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
            'topClients' => $top5Clients,
            'entreprises' => $entreprises,
            'filters' => [
                'period' => $period,
                'entreprise_id' => $entrepriseId,
                'start' => $startDate,
                'end' => $endDate
            ]
        ]);
    }

    public function fiscalite() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $entrepriseId = $_GET['entreprise_id'] ?? 'all';
        $period = $_GET['period'] ?? 'this_month';
        
        $startDate = '1970-01-01';
        $endDate = '2099-12-31';
        if ($period === 'this_month') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        } elseif ($period === 'last_month') {
            $startDate = date('Y-m-01', strtotime('first day of last month'));
            $endDate = date('Y-m-t', strtotime('last day of last month'));
        } elseif ($period === 'this_year') {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        } elseif ($period === 'custom') {
            $startDate = $_GET['start'] ?? $startDate;
            $endDate = $_GET['end'] ?? $endDate;
        }

        $db = Mouvement::getConnection();
        $mouvementsQuery = "SELECT * FROM mouvements WHERE date_mouvement BETWEEN :start AND :end";
        $mouvementsParams = ['start' => $startDate, 'end' => $endDate];
        
        $entreprise = null;
        if ($entrepriseId !== 'all') {
            $mouvementsQuery .= " AND entreprise_id = :eid";
            $mouvementsParams['eid'] = $entrepriseId;
            $entreprises = \App\Models\Entreprise::fari(['id' => $entrepriseId]);
            if (!empty($entreprises)) $entreprise = $entreprises[0];
        }

        $stmtMouv = $db->prepare($mouvementsQuery);
        $stmtMouv->execute($mouvementsParams);
        $mouvements = $stmtMouv->fetchAll(\PDO::FETCH_OBJ);

        $ca_ht = 0;
        $tva_collectee = 0;
        $brs_retenue = 0;

        foreach ($mouvements as $m) {
            if ($m->type === 'entree') {
                $ca_ht += ($m->montant - $m->tva_portion);
                $tva_collectee += $m->tva_portion;
                if (isset($m->brs_portion)) {
                    $brs_retenue += $m->brs_portion;
                }
            }
        }

        return MadelineView::render('reports/rapport_fiscal', [
            'entreprise' => $entreprise,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period,
            'ca_ht' => $ca_ht,
            'tva_collectee' => $tva_collectee,
            'brs_retenue' => $brs_retenue,
            'total_ttc' => $ca_ht + $tva_collectee - $brs_retenue
        ]);
    }
}