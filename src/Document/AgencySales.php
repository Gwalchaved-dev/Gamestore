<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class AgencySales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "string")]
    private $agencyId; // Référence à l'agence dans MySQL

    #[ODM\Field(type: "string")]
    private $genre; // Genre de jeu

    #[ODM\Field(type: "int")]
    private $totalCopiesSold; // Nombre total d'exemplaires vendus par cette agence

    #[ODM\Field(type: "float")]
    private $totalRevenue; // Revenu total généré par cette agence pour ce genre ou jeu

    #[ODM\Field(type: "date")]
    private $saleDate; // Date des ventes

    // Getters et setters...
}