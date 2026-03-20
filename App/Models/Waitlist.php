<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

/**
 * Modèle: Waitlist (Inscriptions anticipées)
 */
class Waitlist extends MadelineORM {
    protected string $table = 'waitlist';

    public int $id;
    public string $email;
    public string $name = '';
    public string $entreprise = '';
    public string $statut = 'en_attente'; // en_attente, invite, refuse
    public string $created_at;
}
