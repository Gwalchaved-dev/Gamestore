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

    // Relation ManyToOne avec le panier (ShoppingCart)
    #[ORM\ManyToOne(targetEntity: ShoppingCart::class, inversedBy: 'cartJeuxVideos')] // Utiliser 'cartJeuxVideos' défini dans ShoppingCart
    #[ORM\JoinColumn(nullable: false)]
    private ?ShoppingCart $shoppingCart = null;

    // Relation ManyToOne avec JeuxVideos
    #[ORM\ManyToOne(targetEntity: JeuxVideos::class)]
    #[ORM\JoinColumn(name: 'jeux_video_id', referencedColumnName: 'id', nullable: false)] // Correction ici avec 'jeux_video_id'
    private ?JeuxVideos $jeuxVideo = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // Getters et Setters pour ShoppingCart
    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shoppingCart;
    }

    public function setShoppingCart(?ShoppingCart $shoppingCart): static
    {
        $this->shoppingCart = $shoppingCart;
        return $this;
    }

    // Getters et Setters pour JeuxVideos
    public function getJeuxVideo(): ?JeuxVideos
    {
        return $this->jeuxVideo;
    }

    public function setJeuxVideo(?JeuxVideos $jeuxVideo): static
    {
        $this->jeuxVideo = $jeuxVideo;
        return $this;
    }

    // Getters et Setters pour Quantité
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }
}
