<?php

namespace App\Entity;

use App\Repository\BookToCellRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookToCellRepository::class)]
class BookToCell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\ManyToOne(cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cell $cell = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $bronedTo = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?BookStatus $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn]
    private ?User $bronedUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getCell(): ?Cell
    {
        return $this->cell;
    }

    public function setCell(?Cell $cell): static
    {
        $this->cell = $cell;

        return $this;
    }

    public function getBronedTo(): ?\DateTime
    {
        return $this->bronedTo;
    }

    public function setBronedTo(?\DateTime $bronedTo): static
    {
        $this->bronedTo = $bronedTo;

        return $this;
    }

    public function getStatus(): ?BookStatus
    {
        return $this->status;
    }

    public function setStatus(?BookStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getBronedUser():?User
    {
        return $this->bronedUser;
    }

    public function setBronedUser(?User $bronedUser):static
    {
        $this->bronedUser = $bronedUser;
        return $this;
    }




}
