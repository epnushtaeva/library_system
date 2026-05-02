<?php


namespace App\Service;


use App\Constants\AuthorFormValidationMessages;
use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;

class AuthorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookService $bookService
    )
    {

    }

    public function addOrEditAuthor(Author $author){
           $this->entityManager->persist($author);
           $this->entityManager->flush();
    }

    public function removeAuthor(Author $author):string
    {
       if($this->bookService->getBooksCount($author->getId()) > 0){
           return AuthorFormValidationMessages::EXISTS_BOOK_ERROR_MESSAGE;
       }

        $this->entityManager->remove($author);
        $this->entityManager->flush();
        return '';
    }
}
