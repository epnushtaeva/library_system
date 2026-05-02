<?php


namespace App\Service;


use App\Constants\JanreErrorMessages;
use App\Entity\Janre;
use Doctrine\ORM\EntityManagerInterface;

class JanreService
{
   public function __construct(
       private EntityManagerInterface $entityManager,
       private BookService $bookService
   )
   {

   }

   public function addOrEditJanre(Janre $janre)
   {
       $this->entityManager->persist($janre);
       $this->entityManager->flush();
   }

   public function removeJanre(Janre $janre):string
   {
       if($this->bookService->getBooksCountForJanre($janre->getId()) > 0){
           return JanreErrorMessages::EXISTS_BOOKS_ERROR_MESSAGE;
       }

       $this->entityManager->remove($janre);
       $this->entityManager->flush();
       return '';
   }
}
