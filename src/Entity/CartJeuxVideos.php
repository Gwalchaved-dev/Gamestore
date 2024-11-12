<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CartJeuxVideos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: JeuxVideos::class)]
    private ?JeuxVideos $jeuxVideo = null;

    #[ORM\ManyToOne(targetEntity: ShoppingCart::class, inversedBy: 'cartJeuxVideos')]
    private ?ShoppingCart $shoppingCart = null;

    #[ORM\ManyToOne(targetEntity: Command::class, inversedBy: 'cartJeuxVideos')]
    private ?Command $command = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJeuxVideo(): ?JeuxVideos
    {
        return $this->jeuxVideo;
    }

    public function setJeuxVideo(?JeuxVideos $jeuxVideo): self
    {
        $this->jeuxVideo = $jeuxVideo;
        return $this;
    }

    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shoppingCart;
    }

    public function setShoppingCart(?ShoppingCart $shoppingCart): self
    {
        $this->shoppingCart = $shoppingCart;
        return $this;
    }

    // Modification pour rendre la méthode plus intuitive
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getCommand(): ?Command
    {
        return $this->command;
    }

    public function setCommand(?Command $command): self
    {
        $this->command = $command;
        return $this;
    }

    // Récupérer le titre du jeu depuis l'entité JeuxVideos
    public function getGameTitre(): ?string
    {
        return $this->jeuxVideo ? $this->jeuxVideo->getTitre() : null;
    }
}

