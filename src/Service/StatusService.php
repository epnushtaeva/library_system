<?php


namespace App\Service;

use App\Entity\BookStatus;
use App\Repository\BookStatusRepository;

class StatusService
{

    public function __construct(private BookStatusRepository $bookStatusRepository)
    {
    }

    public function getBronedStatus():BookStatus
    {
        return $this->bookStatusRepository->find(7);
    }

    public function getDefaultStatus():BookStatus
    {
        return $this->bookStatusRepository->find(9);
    }

    public function getOnHandStatus():BookStatus
    {
        return $this->bookStatusRepository->find(8);
    }
}
