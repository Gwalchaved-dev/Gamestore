<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?int $JeuxVideosID = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateVente = null;

    #[ORM\Column]
    private ?float $PrixVente = null;

    #[ORM\Column]
    private ?int $QuantitéVendue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJeuxVideosID(): ?int
    {
        return $this->JeuxVideosID;
    }

    public function setJeuxVideosID(int $JeuxVideosID): static
    {
        $this->JeuxVideosID = $JeuxVideosID;

        return $this;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->DateVente;
    }

    public function setDateVente(\DateTimeInterface $DateVente): static
    {
        $this->DateVente = $DateVente;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->PrixVente;
    }

    public function setPrixVente(float $PrixVente): static
    {
        $this->PrixVente = $PrixVente;

        return $this;
    }

    public function getQuantitéVendue(): ?int
    {
        return $this->QuantitéVendue;
    }

    public function setQuantitéVendue(int $QuantitéVendue): static
    {
        $this->QuantitéVendue = $QuantitéVendue;

        return $this;
    }
}
