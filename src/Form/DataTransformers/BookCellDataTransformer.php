<?php


namespace App\Form\DataTransformers;


use App\Entity\BookToCell;
use App\Entity\Cell;
use App\Repository\BookStatusRepository;
use App\Repository\BookToCellRepository;
use App\Repository\CellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class BookCellDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private CellRepository $cellRepository,
        private BookToCellRepository $bookToCellRepository,
        private BookStatusRepository $bookStatusRepository
    )
    {
    }

    public function transform(mixed $cells): mixed
    {
        if(!$cells){
            return '';
        }

        $names = [];

        foreach($cells as $cell){
            $names[] = $cell->getCell()->getCellNumber();
        }

        return implode(',', $names);
    }

    public function reverseTransform(mixed $cells): mixed
    {
        $resArray = array_map(function($cellName){
              $existingCell = $this->cellRepository->findOneBy(['cellNumber' => $cellName]);
              $bookToCell = null;

              if($existingCell){
                  $bookToCell = $this->bookToCellRepository->findOneByCellId($existingCell->getId());

                  if(!$bookToCell){
                      $bookToCell = new BookToCell();
                      $bookToCell->setCell($existingCell);
                      $bookToCell->setStatus($this->bookStatusRepository->findOneBy(['id' => 7]));
                  }
              } else {
                  $existingCell = new Cell();
                  $existingCell->setCellNumber($cellName);
                  $existingCell->setPrevCell($this->cellRepository->findLastCell());
                  $bookToCell = new BookToCell();
                  $bookToCell->setCell($existingCell);
                  $bookToCell->setStatus($this->bookStatusRepository->findOneBy(['id' => 7]));
              }

            if(!$bookToCell){
                $bookToCell = new BookToCell();
                $bookToCell->setCell($existingCell);
                $bookToCell->setStatus($this->bookStatusRepository->findOneBy(['id' => 7]));
            }

              return $bookToCell;
        }, explode(',', $cells));
        $res = new ArrayCollection();

        foreach($resArray as $cell){
            $res->add($cell);
        }


        return $res;
    }
}
