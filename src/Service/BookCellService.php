<?php


namespace App\Service;


use App\Entity\Book;
use App\Entity\BookToCell;
use App\Repository\BookToCellRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookCellService
{
    public function __construct(
        private BookToCellRepository $bookToCellRepository,
        private EntityManagerInterface $entityManager,
        private CellService $cellService,
        private StatusService $statusService
    )
    {
    }

    public function setBookCellOnHandStatus(BookToCell $bookToCell):void
    {
        $bookToCell->setStatus($this->statusService->getOnHandStatus());
        $bookToCell->setBronedTo(null);
        $this->entityManager->flush();
    }

    public function getBookCells(int $bookId):array
    {
       return $this->bookToCellRepository->getByBookId($bookId);
    }

    public function getBooksForEmail():array
    {
        $page = 1;
        $allBooks = [];
        $books = $this->bookToCellRepository->getBooksForMail($page);

        while (sizeof($books) > 0){
            foreach ($books as $book){
                array_push($allBooks, $book);
            }
            $page++;
            $books = $this->bookToCellRepository->getBooksForMail($page);
        }

        return $allBooks;
    }

    public function unBookOldBooks()
    {
        $page = 1;
        $oldBooked = $this->bookToCellRepository->getOldBookingBooks($page);

        while(sizeof($oldBooked) > 0){
            foreach ($oldBooked as $book){
                $this->unBook($book);
            }

            $page++;
            $oldBooked = $this->bookToCellRepository->getOldBookingBooks($page);
        }
    }

    public function updateCells(Book $book):void
    {
        $this->removeExistingCells($book);
        $this->addNotExistingCells($book);
    }

    public function addNotExistingCells(Book $book):void
    {
        foreach ($book->getCells() as $cell) {
            $cell->setBook($book);
            $this->cellService->addCell($cell->getCell());
            $this->entityManager->persist($cell);
            $this->entityManager->flush();
        }
    }

    public function removeExistingCells(Book $book)
    {
        if ($book->getId()) {
            $bookCells = $this->bookToCellRepository->getNamesByBookId($book->getId());
            $newCellNames = [];

            foreach($book->getCells() as $cell){
                $newCellNames[]=$cell->getCell()->getCellNumber();
            }

            foreach ($bookCells as $existingCell) {
                if (!in_array($existingCell['cellName'], $newCellNames)) {
                    $this->entityManager->remove($this->entityManager->getReference(BookToCell::class, $existingCell['cellId']));
                    $this->entityManager->flush();
                }
            }
        }
    }

    public function removeAllCells(Book $book):void
    {
        foreach($book->getCells() as $cell){
            $this->entityManager->remove($cell);
            $this->entityManager->flush();
        }
    }


    public function unBook(BookToCell $book):void
    {
        $book->setBronedTo(null);
        $book->setBronedUser(null);
        $book->setStatus($this->statusService->getDefaultStatus());
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $book->getBook()->setFreeCount($book->getBook()->getFreeCount() + 1);

        if($book->getBook()->getBronedCount()>0) {
            $book->getBook()->setBronedCount($book->getBook()->getBronedCount() - 1);
        }

        $this->entityManager->persist($book->getBook());
        $this->entityManager->flush();
    }
}
