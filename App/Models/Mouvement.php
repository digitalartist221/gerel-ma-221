<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

/**
 * Modèle: Mouvement (Journal de Caisse Centralisé)
 * Gère les Entrées (Ventes) et Sorties (Dépenses, Salaires, Loyer)
 */
class Mouvement extends MadelineORM {
    protected string $table = 'mouvements';

    public string $type; // entree, sortie
    public string $libelle;
    public float $montant;
    public string $date_mouvement;
    public string $categorie; // Vente, Salaire, Loyer, Charge, Autre
    public ?string $document_id = null; // Lien optionnel vers une facture/BC
    public int $entreprise_id;
    public int $user_id;
    
    // Champs fiscaux pour le reporting
    public float $tva_portion = 0.0;
    public float $brs_portion = 0.0;
}
