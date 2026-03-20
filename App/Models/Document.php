<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

class Document extends MadelineORM {
    protected string $table = 'documents';

    public string $type; // Facture, BC, BL, Contrat
    public string $numero;
    public string $client_id;
    public ?string $entreprise_id = null;
    public ?string $parent_id = null; // ID du document source (ex: BC -> BL)
    public float $total_ht = 0.0;
    public float $total_ttc = 0.0;
    public string $statut = 'brouillon'; // brouillon, envoye, valide, paye, refuse, signe
    public ?int $is_read = 0;
    public ?string $read_at = null;
    public ?string $sent_at = null;
    public string $token_public;
    public string $date_emission;
    public ?string $signed_at = null;
    public ?string $signed_by = null;
    public ?string $signature_hash = null;
    public float $tva_amount = 0.0;
    public float $brs_amount = 0.0;
    public float $vrs_amount = 0.0;
    public int $tax_enabled = 0;
    public int $stock_reduced = 0;
    public string $contenu_json = ''; // Détails des lignes de facture ou corps du contrat
}
