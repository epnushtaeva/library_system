<?php

namespace App\Entity;

use App\Repository\CellRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CellRepository::class)]
class Cell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cellNumber = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $nextCell = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $prevCell = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCellNumber(): ?string
    {
        return $this->cellNumber;
    }

    public function setCellNumber(string $cellNumber): static
    {
        $this->cellNumber = $cellNumber;

        return $this;
    }

    public function getNextCell(): ?self
    {
        return $this->nextCell;
    }

    public function setNextCell(?self $nextCell): static
    {
        $this->nextCell = $nextCell;

        return $this;
    }

    public function getPrevCell(): ?self
    {
        return $this->prevCell;
    }

    public function setPrevCell(?self $prevCell): static
    {
        $this->prevCell = $prevCell;

        return $this;
    }
}
