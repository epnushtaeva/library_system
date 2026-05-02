<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function getCountByAuthorId(int $authorId): int
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->join('b.author', 'c')
            ->andWhere('c.id = :authorId')
            ->setParameter('authorId', $authorId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getCountByJanreId(int $janreId): int
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->join('b.janre', 'j')
            ->andWhere('j.id = :janreId')
            ->setParameter('janreId', $janreId)
            ->getQuery()
            ->getSingleScalarResult();

    }
}
