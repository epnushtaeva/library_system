<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2500)]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

    #[ORM\Column]
    private ?int $allCount = null;

    #[ORM\Column]
    private ?int $freeCount = null;

    #[ORM\Column]
    private ?int $bronedCount = null;

    #[ORM\Column]
    private ?int $onHandCount = null;

    #[ORM\Column]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Janre $janre = null;

    #[ORM\OneToMany(targetEntity: BookToCell::class, mappedBy: 'book', cascade: ["persist"])]
    private ArrayCollection|PersistentCollection $cells;

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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getAllCount(): ?int
    {
        return $this->allCount;
    }

    public function setAllCount(int $allCount): static
    {
        $this->allCount = $allCount;

        return $this;
    }

    public function getFreeCount(): ?int
    {
        return $this->freeCount;
    }

    public function setFreeCount(int $freeCount): static
    {
        $this->freeCount = $freeCount;

        return $this;
    }

    public function getBronedCount(): ?int
    {
        return $this->bronedCount;
    }

    public function setBronedCount(int $bronedCount): static
    {
        $this->bronedCount = $bronedCount;

        return $this;
    }

    public function getOnHandCount(): ?int
    {
        return $this->onHandCount;
    }

    public function setOnHandCount(int $onHandCount): static
    {
        $this->onHandCount = $onHandCount;

        return $this;
    }

    public function getJanre(): ?Janre
    {
        return $this->janre;
    }

    public function setJanre(?Janre $janre): static
    {
        $this->janre = $janre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCells():ArrayCollection|PersistentCollection
    {
        return $this->cells;
    }


    public function setCells(ArrayCollection|PersistentCollection $cells):static
    {
        $this->cells = $cells;
        return $this;
    }




}
