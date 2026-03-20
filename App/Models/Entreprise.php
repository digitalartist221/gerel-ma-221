<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

class Entreprise extends MadelineORM {
    protected string $table = 'entreprises';

    public string $nom;
    public ?string $siret = null;
    public ?string $logo = null;
    public ?string $adresse = null;
    public ?string $ville = null;
    public ?string $email = null;
    public ?string $contact = null;
    public ?string $ninea = null;
    public ?string $rc = null;
    public ?string $website = null;
}
