<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

/**
 * Page: Modèle Contrat
 * Author: Digital Artist Studio
 * Framework: Madeline (Wolof Edition)
 */
class Contrat extends MadelineORM {
    protected string $table = 'contrats';

    public string $numero;
    public string $client_id;
    public ?string $entreprise_id = null;
    public float $total_ht = 0.0;
    public float $total_ttc = 0.0;
    public string $statut = 'brouillon'; // brouillon, envoye, valide, signe, annule
    public ?int $is_read = 0;
    public ?string $read_at = null;
    public ?string $sent_at = null;
    public string $token_public;
    public string $date_signature;
    public string $contenu_json = ''; // Détails des articles/prestations
    public string $notes = ''; // Texte libre du contrat (HTML)

    // Suivi Signature
    public ?string $signed_at = null;
    public ?string $signed_by = null;
    public ?string $signature_hash = null;
    public ?string $signature_client_hash = null;
    public ?string $signed_client_at = null;
}