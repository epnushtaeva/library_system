<?php


namespace App\Service;


use App\Constants\BookFormVialotionMessages;
use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{

    public function __construct(
        private BookRepository  $bookRepository,
        private BookCellService $bookCellService,
        private StatusService $statusService,
        private EntityManagerInterface $entityManager,
        private MailService $mailService
    )
    {
    }

    public function sendBookEmail()
    {
        $booksForMail = $this->bookCellService->getBooksForEmail();
        $infoForEmail = $this->combineBooksInfo($booksForMail);

        foreach($infoForEmail as $email=>$info){
            $this->mailService->sendBookEmail($email, implode(', ', $info['bookNames']),  $info['daysCount']);
        }
    }

    private function combineBooksInfo(array $booksForSendInfo):array
    {
        $infoForEmail = [];

        foreach ($booksForSendInfo as $book){
            if(!in_array($book->getBronedUser()->getEmail(), array_keys($infoForEmail))){
                $infoForEmail[$book->getBronedUser()->getEmail()] = [
                    'bookNames' => [],
                    'daysCount' => 3
                ];
            }

            $infoForEmail[$book->getBronedUser()->getEmail()]['bookNames'][] = '"'.$book->getBook()->getName().'"';
            $bookedTo = $book->getBronedTo();
            $interval = $bookedTo->diff(new \DateTime());

            if($interval->days < $infoForEmail[$book->getBronedUser()->getEmail()]['daysCount']){
                $infoForEmail[$book->getBronedUser()->getEmail()]['daysCount'] = $interval->days;
            }
        }

        return $infoForEmail;
    }

    public function addOrUpdateBook(Book $book):void
    {
        $tempCells = $book->getCells();
        $book->setCells(new ArrayCollection());
        $book->setFreeCount($book->getAllCount());
        $book->setOnHandCount(0);
        $book->setBronedCount(0);
        $this->entityManager->persist($book);
        $this->entityManager->flush();
        $book->setCells($tempCells);
        $this->bookCellService->updateCells($book);
    }

    public function removeBook(Book $book):void
    {
        $this->bookCellService->removeAllCells($book);
        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    public function getBooksCount(int $authorId):int
    {
      return $this->bookRepository->getCountByAuthorId($authorId);
    }

    public function getBooksCountForJanre(int $janreId):int
    {
        return $this->bookRepository->getCountByJanreId($janreId);
    }

    public function broneBook(Book $book, User $user):string
    {
        if($book->getFreeCount() == 0){
            return BookFormVialotionMessages::NO_FREE_BOOK_FOR_BRONE;
        }

        if($user->getBroneBooks()->count() >= 5){
            return BookFormVialotionMessages::MORE_FIVE_BOOKS;
        }

        $this->entityManager->getConnection()->setTransactionIsolation(\Doctrine\DBAL\TransactionIsolationLevel::SERIALIZABLE);
        $this->entityManager->wrapInTransaction(function() use($book, $user) {
            $book->setBronedCount($book->getBronedCount() + 1);
            $book->setFreeCount($book->getFreeCount() - 1);
            $this->entityManager->persist($book);
            $this->entityManager->flush();

            $freeCell = null;

            foreach($book->getCells() as $cell){
                if($cell->getStatus()->getId() == 9){
                       $freeCell = $cell;
                       break;
                }
            }

            if($freeCell){
                $currentDate = new \DateTime();
                $currentDate->modify('+3 days');
                $freeCell->setStatus($this->statusService->getBronedStatus());
                $freeCell->setBronedTo($currentDate);
                $freeCell->setBronedUser($user);
                $this->entityManager->persist($freeCell);
                $this->entityManager->flush();
            }
        });

        return '';
    }
}
