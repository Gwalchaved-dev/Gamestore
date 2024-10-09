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

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    private ?Order $order = null;

    #[ORM\ManyToOne(targetEntity: CartJeuxVideos::class)]
    private ?CartJeuxVideos $cartJeuxVideo = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;
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