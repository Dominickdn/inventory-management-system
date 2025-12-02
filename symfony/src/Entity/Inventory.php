<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
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

    #[ORM\Column(type: Types::INTEGER, options: ["default" => 0])]
    private int $available = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $stockLimit = null;

    #[ORM\Column(type: Types::INTEGER, options: ["default" => 0])]
    private int $total = 0;

    /**
     * @var Collection<int, AssignedItems>
     */
    #[ORM\OneToMany(targetEntity: AssignedItems::class, mappedBy: 'inventoryId', orphanRemoval: true)]
    private Collection $assignedItems;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->assignedItems = new ArrayCollection();
    }

    public function increase(string $field, int $assignedCount = 0): bool
    {
        switch ($field) {

            case 'total':
                if ($this->stockLimit !== null && $this->total >= $this->stockLimit) {
                    return false;
                }
                $this->total++;
                $this->available = $this->total - $assignedCount;
                return true;

            case 'stockLimit':
                $this->stockLimit = ($this->stockLimit ?? 0) + 1;
                return true;

            case 'available':
                if ($this->available < $this->total - $assignedCount) {
                    $this->available++;
                    return true;
                }
                return false;
        }
        return false;
    }

    public function decrease(string $field, int $assignedCount = 0): bool
    {
        switch ($field) {

            case 'total':
                if ($this->total <= $assignedCount) {
                    return false;
                }
                $this->total--;
                $this->available = $this->total - $assignedCount;
                return true;

            case 'stockLimit':
                if (($this->stockLimit ?? 0) > 0) {
                    $this->stockLimit--;
                    return true;
                }
                return false;

            case 'available':
                if ($this->available > 0) {
                    $this->available--;
                    return true;
                }
                return false;
        }
        return false;
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

    public function getTotal(): ?int
    {
        return $this->total;
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
}