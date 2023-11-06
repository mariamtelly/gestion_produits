<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

   /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findMostExpensive($maxResults = 0): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            '
            SELECT p
            FROM App\Entity\Produit p
            ORDER BY p.prix DESC
            '
        );
        
        if($maxResults > 0)
        {
            $query->setMaxResults($maxResults);
        }

        return $query->getResult();
    }

    public function findMostViewed($maxResults = 0): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            '
            SELECT p 
            FROM App\Entity\Produit p
            ORDER BY p.nombreDeVues DESC
            '
        );
        
        if($maxResults > 0){
            $query->setMaxResults($maxResults);
        }
        

        return $query->getResult();
    }

    public function findRandomProducts($maxResults = 0): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            '
            SELECT p 
            FROM App\Entity\Produit p
            '
        );

        if($maxResults > 0)
        {
            $query->setMaxResults($maxResults);
        }

        return $query->getResult();
    }

    public function findProductsByCategorie($categorie, $maxResults = 0): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            '
            SELECT p
            FROM App\Entity\Produit p
            WHERE p.categorie = :categorie
            ORDER BY p.prix ASC
            '
        )->setParameters(['categorie' => $categorie]);

        if($maxResults > 0)
        {
            $query->setMaxResults($maxResults);
        }

        return $query->getResult();
    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
