<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use BcMath\Number;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $available = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $stockLimit = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $total = null;

    /**
     * @var Collection<int, AssignedItems>
     */
    #[ORM\OneToMany(targetEntity: AssignedItems::class, mappedBy: 'inventoryId', orphanRemoval: true)]
    private Collection $assignedItems;

    public function __construct()
    {
        $this->assignedItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(?int $available): static
    {
        $this->available = $available;
        return $this;
    }

    public function getStockLimit(): ?int
    {
        return $this->stockLimit;
    }

    public function setStockLimit(?int $stockLimit): static
    {
        $this->stockLimit = $stockLimit;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection<int, AssignedItems>
     */
    public function getAssignedItems(): Collection
    {
        return $this->assignedItems;
    }

    public function addAssignedItem(AssignedItems $assignedItem): static
    {
        if (!$this->assignedItems->contains($assignedItem)) {
            $this->assignedItems->add($assignedItem);
            $assignedItem->setInventoryId($this);
        }

        return $this;
    }

    public function removeAssignedItem(AssignedItems $assignedItem): static
    {
        if ($this->assignedItems->removeElement($assignedItem)) {
            // set the owning side to null (unless already changed)
            if ($assignedItem->getInventoryId() === $this) {
                $assignedItem->setInventoryId(null);
            }
        }

        return $this;
    }
}
