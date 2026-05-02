<?php


namespace App\Service;


use App\Entity\Cell;
use Doctrine\ORM\EntityManagerInterface;

class CellService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function addCell(Cell $cell):void
    {
            $this->entityManager->persist($cell);
            $this->entityManager->flush();

            if($cell->getPrevCell() && !$cell->getPrevCell()->getNextCell()) {
                $cell->getPrevCell()->setNextCell($cell);
                $this->entityManager->persist($cell->getPrevCell());
                $this->entityManager->flush();
            }

    }
}
