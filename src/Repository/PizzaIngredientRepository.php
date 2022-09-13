<?php

namespace App\Repository;

use App\Entity\PizzaIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PizzaIngredient>
 *
 * @method PizzaIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method PizzaIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method PizzaIngredient[]    findAll()
 * @method PizzaIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PizzaIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PizzaIngredient::class);
    }

    public function add(PizzaIngredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PizzaIngredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PizzaIngredient[] Returns an array of PizzaIngredient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PizzaIngredient
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
