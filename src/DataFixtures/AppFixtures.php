<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookStatus;
use App\Entity\BookToCell;
use App\Entity\Cell;
use App\Entity\Janre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('en_US');
    }

    public function load(ObjectManager $manager): void
    {
        $janres = $this->fillBookJanres($manager);
        $status = $this->fillBookStatuses($manager);
        $this->fillBooks($janres[random_int(0, 2)], $manager, $status);
    }

    public function fillBookJanres(ObjectManager $manager):array
    {
        $janres = [];

        $janre = new Janre();
        $janre->setName("Роман");
        $manager->persist($janre);
        $janres[] = $janre;

        $janre = new Janre();
        $janre->setName("Детектив");
        $manager->persist($janre);
        $janres[] = $janre;

        $janre = new Janre();
        $janre->setName("Классика");
        $manager->persist($janre);
        $janres[] = $janre;
        return $janres;
    }

    public function fillBookStatuses(ObjectManager $manager):BookStatus
    {
        $status = new BookStatus();
        $status->setName("Забронирована");
        $manager->persist($status);

        $status = new BookStatus();
        $status->setName("На руках");
        $manager->persist($status);

        $status = new BookStatus();
        $status->setName("Доступна для бронирования");
        $manager->persist($status);

        $manager->flush();
        return $status;
    }

    public function makeBookToCell(Cell $cell, Book $book, BookStatus $status, ObjectManager $manager)
    {
        $bookCell = new BookToCell();
        $bookCell->setCell($cell);
        $manager->persist($book);
        $bookCell->setBook($book);
        $bookCell->setStatus($status);
        $manager->persist($bookCell);
        $manager->flush();
    }

    public function fillBooks($janres, ObjectManager $manager, BookStatus $status)
    {
        $cells = 'ABCDEFGHIJ';
        $prevCell = null;
        $currentCellsCount = 0;

        for ($i = 0; $i < 100; $i++) {
            $book = $this->makeBook($manager, $janres);

            for ($cellIndex = 0; $cellIndex < $book->getAllCount(); $cellIndex++) {
                $cell = new Cell();
                $cell->setCellNumber(substr($cells, intval(floor($currentCellsCount / 30)), 1) . (strval($currentCellsCount % 31)));
                $manager->persist($cell);
                $manager->flush();

                if ($prevCell) {
                    $cell->setPrevCell($prevCell);
                    $prevCell->setNextCell($cell);
                    $manager->persist($cell);
                    $manager->flush();
                    $manager->persist($prevCell);
                    $manager->flush();
                }

                $this->makeBookToCell($cell, $book, $status, $manager);

                $prevCell = $cell;

                $currentCellsCount++;
            }

        }
    }

    public function makeBook(ObjectManager $manager, $janres):Book
    {
        $book = new Book();
        $book->setName($this->faker->name());
        $book->setDescription($this->faker->name());
        $book->setAllCount(random_int(1, 3));
        $book->setFreeCount($book->getAllCount());
        $book->setOnHandCount(0);
        $book->setJanre($janres);
        $book->setBronedCount(0);
        $book->setAuthor($this->makeAuthor($manager));
        $manager->persist($book);
        $manager->flush();
        return $book;
    }

    public function makeAuthor(ObjectManager $manager):Author
    {
        $author = new Author();
        $author->setFullName($this->faker->name());
        $author->setAddress($this->faker->name());
        $author->setBirthDate($this->faker->dateTime());
        $manager->persist($author);
        $manager->flush();
        return $author;
    }
}
