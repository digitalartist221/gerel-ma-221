<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

class ArticleJuridique extends MadelineORM {
    protected string $table = 'articles_juridiques';

    public string $numero; // Ex: ARTICLE 1, CLAUSE 2
    public string $nom;
    public ?string $description = null;
    public ?string $entreprise_id = null;
}
