<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

class Client extends MadelineORM {
    protected string $table = 'clients';

    public string $nom;
    public ?string $email = null;
    public ?string $telephone = null;
    public ?string $adresse = null;
    public ?string $ville = null;
    public ?string $ninea = null;
    public ?string $rc = null;
    public ?string $website = null;
    public ?string $entreprise_id = null;
}
