<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

class Produit extends MadelineORM {
    protected string $table = 'produits';

    public string $designation;
    public ?string $entreprise_id = null;
    public float $prix_unitaire = 0.0;
    public float $tva = 18.0;
    public int $stock = 0;
}
