<?php

namespace App\Entity;

use App\Repository\AssignedItemsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssignedItemsRepository::class)]
class AssignedItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serialNumber = null;

    #[ORM\ManyToOne(inversedBy: 'assignedItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'assignedItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inventory $inventoryId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $assignedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qualityDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(?string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->userId;
    }

    public function setUser(?User $user): static
    {
        $this->userId = $user;
        return $this;
    }

    public function getInventoryId(): ?Inventory
    {
        return $this->inventoryId;
    }

    public function setInventoryId(?Inventory $inventoryId): static
    {
        $this->inventoryId = $inventoryId;

        return $this;
    }

    public function getAssignedAt(): ?\DateTimeImmutable
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(\DateTimeImmutable $assignedAt): static
    {
        $this->assignedAt = $assignedAt;

        return $this;
    }

    public function getQualityDescription(): ?string
    {
        return $this->qualityDescription;
    }

    public function setQualityDescription(?string $qualityDescription): static
    {
        $this->qualityDescription = $qualityDescription;

        return $this;
    }
}
