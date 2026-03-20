<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

/**
 * Modèle: Dépense (Suivi des sorties d'argent)
 */
class Depense extends MadelineORM {
    public int $id;
    public string $titre;
    public float $montant;
    public string $categorie;
    public string $date_depense;
    public int $entreprise_id;
    public int $user_id;
}
