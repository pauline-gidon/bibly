<?php

namespace App\Entity;

use App\Repository\LibraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryRepository::class)]
class Library
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'library')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'library', targetEntity: Shelf::class)]
    private ?Collection $shelfs;

    public function __construct()
    {
        $this->shelfs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Shelf>
     */
    public function getShelfs(): Collection
    {
        return $this->shelfs;
    }

    public function addShelf(Shelf $shelf): self
    {
        if (!$this->shelfs->contains($shelf)) {
            $this->shelfs->add($shelf);
            $shelf->setLibrary($this);
        }

        return $this;
    }

    public function removeShelf(Shelf $shelf): self
    {
        if ($this->shelfs->removeElement($shelf)) {
            // set the owning side to null (unless already changed)
            if ($shelf->getLibrary() === $this) {
                $shelf->setLibrary(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }
}
