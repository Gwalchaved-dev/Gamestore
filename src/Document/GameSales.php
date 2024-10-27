<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GameSales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "int")]
    private ?int $gameId = null;

    #[ODM\Field(type: "date")]
    private ?\DateTimeInterface $saleDate = null;

    #[ODM\Field(type: "int")]
    private ?int $copiesSold = null;

    public function getId()
    {
        return $this->id;
    }

    public function getGameId(): ?int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): self
    {
        $this->gameId = $gameId;
        return $this;
    }

    public function getSaleDate(): ?\DateTimeInterface
    {
        return $this->saleDate;
    }

    public function setSaleDate(\DateTimeInterface $saleDate): self
    {
        $this->saleDate = $saleDate;
        return $this;
    }

    public function getCopiesSold(): ?int
    {
        return $this->copiesSold;
    }

    public function setCopiesSold(int $copiesSold): self
    {
        $this->copiesSold = $copiesSold;
        return $this;
    }
}