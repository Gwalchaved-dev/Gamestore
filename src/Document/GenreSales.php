<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GenreSales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "string")]
    private $genre; // Genre de jeu

    #[ODM\Field(type: "int")]
    private $totalCopiesSold; // Nombre total d'exemplaires vendus pour ce genre

    #[ODM\Field(type: "float")]
    private $totalRevenue; // Revenu total généré par ce genre

    #[ODM\Field(type: "date")]
    private $saleDate; // Date des ventes

    // Getters et setters...
}