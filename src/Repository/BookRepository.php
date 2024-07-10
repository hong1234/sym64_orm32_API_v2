<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Book;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository {
    private $manager;

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Book::class);
        $this->manager = $this->getEntityManager();
    }

    public function saveBook($data){
        $book = new Book();
        $book->setTitle($data['title'])
             ->setContent($data['content'])
             ->setCreatedOn(new \DateTime("now"));

        $this->manager->persist($book);
        $this->manager->flush();  
        return $book;    
    }

    public function updateBook(Book $book, $data){
        $book->setTitle($data['title'])
             ->setContent($data['content'])
             ->setUpdatedOn(new \DateTime("now"));
        $this->manager->flush(); 
        return $book;  
    }

    public function removeBook(Book $book){
        $this->manager->remove($book);
        $this->manager->flush();
    }

    public function searchBook(String $searchkey){
        return $this->createQueryBuilder('b')
        ->where('b.title LIKE :searchkey')
        ->setParameter('searchkey', '%'.$searchkey.'%')
        ->orderBy('b.id', 'ASC')
        ->getQuery()
        ->getResult();      
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
