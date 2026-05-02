<?php


namespace App\Filter;

class BookFilter
{
    private ?string $name;
    private ?string $author;
    private array $janres;

    public function getName():?string
    {
        return $this->name;
    }

    public function setName(?string $name):static
    {
        $this->name = $name;
        return $this;
    }

    public function getAuthor():?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author):static
    {
        $this->author = $author;
        return $this;
    }

    public function getJanres():array
    {
        return $this->janres;
    }

    public function setJanres(?array $janres):static
    {
        $this->janres = $janres;
        return $this;
    }





}
