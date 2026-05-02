<?php

namespace App\Repository;

use App\Entity\BookToCell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookToCell>
 */
class BookToCellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookToCell::class);
    }

    public function getBooksForMail(int $page): array
    {
        $now = new \DateTime();
        $now->modify('+3 days');
        return $this->createQueryBuilder('b')
            ->join('b.status', 's')
            ->andWhere('b.bronedTo < :now')
            ->andWhere('s.id=7')
            ->setParameter('now', $now)
            ->setFirstResult(($page - 1) * 50)
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    public function getOldBookingBooks(int $page): array
    {
        $now= new \DateTime();
        return $this->createQueryBuilder('b')
            ->join('b.status', 's')
            ->andWhere('b.bronedTo < :now')
            ->andWhere('s.id=7')
            ->setParameter('now', $now)
            ->setFirstResult(($page - 1) * 50)
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    public function getNamesByBookId(int $bookId): array
    {
            return $this->createQueryBuilder('b')
                ->select('b.id AS cellId')
                ->addSelect('c.cellNumber AS cellName')
                ->join('b.cell', 'c')
                ->join('b.book', 'd')
                ->andWhere('d.id = :bookId')
                ->setParameter('bookId', $bookId)
                ->orderBy('b.id', 'ASC')
                ->getQuery()
                ->getArrayResult()
            ;
    }

    public function getByBookId(int $bookId): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.id AS cellId')
            ->addSelect('c.cellNumber AS cellName')
            ->addSelect('s.name AS statusName')
            ->addSelect('u.fullName AS userFullName')
            ->addSelect('b.bronedTo AS bronedTo')
            ->join('b.cell', 'c')
            ->join('b.book', 'd')
            ->join('b.status', 's')
            ->leftjoin('b.bronedUser', 'u')
            ->andWhere('d.id = :bookId')
            ->setParameter('bookId', $bookId)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findOneByCellId(int $cellId): BookToCell|null
    {
        return $this->createQueryBuilder('b')
            ->join('b.cell', 'c')
            ->andWhere('c.id = :cellId')
            ->setParameter('cellId', $cellId)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    public function findOneBySomeField($value): ?BookToCell
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
