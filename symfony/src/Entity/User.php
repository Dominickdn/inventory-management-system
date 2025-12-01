<?php

namespace App\Entity;


use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $department = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, AssignedItems>
     */
    #[ORM\OneToMany(targetEntity: AssignedItems::class, mappedBy: 'userId', orphanRemoval: true)]
    private Collection $assignedItems;

    public function __construct()
    {
        $this->assignedItems = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    // public function setCreatedAt(\DateTimeImmutable $createdAt): static
    // {
    //     $this->createdAt = $createdAt;

    //     return $this;
    // }

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
            $assignedItem->setUserId($this);
        }

        return $this;
    }

    public function removeAssignedItem(AssignedItems $assignedItem): static
    {
        if ($this->assignedItems->removeElement($assignedItem)) {
            // set the owning side to null (unless already changed)
            if ($assignedItem->getUserId() === $this) {
                $assignedItem->setUserId(null);
            }
        }

        return $this;
    }
}
