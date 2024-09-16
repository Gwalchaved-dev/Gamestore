<?php

namespace App\Entity;

use App\Repository\CartJeuxVideosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartJeuxVideosRepository::class)]
class CartJeuxVideos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ShoppingCartID = null;

    #[ORM\Column]
    private ?int $JeuxVideosID = null;

    #[ORM\Column]
    private ?int $Quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShoppingCartID(): ?int
    {
        return $this->ShoppingCartID;
    }

    public function setShoppingCartID(int $ShoppingCartID): static
    {
        $this->ShoppingCartID = $ShoppingCartID;

        return $this;
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

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): static
    {
        $this->Quantite = $Quantite;

        return $this;
    }
}
