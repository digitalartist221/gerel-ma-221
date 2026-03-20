<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

/**
 * Modèle: Utilisateur (Auto-Migré)
 */
class User extends MadelineORM {
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public ?string $role = 'admin';
    public ?int $parent_id = null;
    public ?int $entreprise_id = null;
}