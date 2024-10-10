<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderItemRepository; // Import de OrderItemRepository

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Command::class, inversedBy: 'items')] // Changer Order à Command
    private ?Command $command = null; // Changer Order à Command

    #[ORM\ManyToOne(targetEntity: CartJeuxVideos::class)]
    private ?CartJeuxVideos $cartJeuxVideo = null;

    #[ORM\Column(name: 'quantite', type: 'integer')] // Utiliser 'quantite' au lieu de 'quantity'
    private int $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommand(): ?Command // Changer Order à Command
    {
        return $this->command; // Changer order à command
    }

    public function setCommand(?Command $command): self // Changer Order à Command
    {
        $this->command = $command; // Changer order à command
        return $this;
    }

    public function getCartJeuxVideo(): ?CartJeuxVideos
    {
        return $this->cartJeuxVideo;
    }

    public function setCartJeuxVideo(?CartJeuxVideos $cartJeuxVideo): self
    {
        $this->cartJeuxVideo = $cartJeuxVideo;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}