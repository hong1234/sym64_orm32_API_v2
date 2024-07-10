<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Book;
use App\Entity\Review;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository {

    private $manager;
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Review::class);
        $this->manager = $this->getEntityManager();
    }

    public function saveReview(Book $book, $data) {
        $review = new Review();
        $review->setName($data['name'])
               ->setEmail($data['email'])
               ->setContent($data['content'])
               ->setCreatedOn(new \DateTime("now"))
               ->setBook($book);
        $this->manager->persist($review);
        $this->manager->flush(); 
        return $review;
    }

    public function updateReview(Review $review, $data) {
        $review->setName($data['name'])
               ->setEmail($data['email'])
               ->setContent($data['content'])
               ->setUpdatedOn(new \DateTime("now"));
        $this->manager->flush();
    }

    public function removeReview(Review $review) {
        $this->manager->remove($review);
        $this->manager->flush();
    }

    public function removeReviews(array $reviews) {
        foreach ($reviews as $review) {
            $this->removeReview($review);
        }
        return 1;
    }

                
    // public function deleteReviewsByBookId($bookId) {
    //     return $this->createQueryBuilder('r')
    //         ->where('r.book = :bookId')
    //         ->setParameter('bookId', $bookId)
    //         ->delete();
    // }

    /**
    * @return Review[] Returns an array of Review objects
    */
    public function getReviewsByBookId($bookId): array {
        return $this->createQueryBuilder('r')
                ->andWhere('r.book = :val')
                ->setParameter('val', $bookId)
                ->orderBy('r.id', 'ASC')
                // ->setMaxResults(10)
                ->getQuery()
                ->getResult();
    }

    //    /**
    //     * @return Review[] Returns an array of Review objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //         //    ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Review
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
