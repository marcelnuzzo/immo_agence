<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    // /**
    //  * @return Categorie[] Returns an array of Categorie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Categorie
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
    * @return Categorie[] Returns an array of Categorie objects
    */
    public function findByCatFilter($libelle)
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.libelle=:libelle')
        ->setParameter('libelle', $libelle)
        ->getQuery()
        ->getResult()
    ;
    }

    public function findAllBienInByCategory()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM categorie,bien
            WHERE categorie.id=bien.categorie_id
            AND categorie.libelle="appartement"
            ';

            $stmt = $conn->prepare($sql);
            $stmt->execute(['libelle' => 'appartement']);

            // returns an array of arrays (i.e. a raw data set)
            return $stmt->fetchAll();

    }
    
}
