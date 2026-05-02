<?php


namespace App\Task;


use App\Message\UpdateBooksMessage;
use App\Service\BookCellService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SetBookUnbookingTask
{
    public function __construct(
        private BookCellService $bookCellService

    )
    {
    }

    public function __invoke(UpdateBooksMessage $updateBooksMessage)
    {
      $this->bookCellService->unBookOldBooks();
    }

}
