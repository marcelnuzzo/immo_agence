<?php

namespace App\Repository;

use App\Entity\Tipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tipe[]    findAll()
 * @method Tipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tipe::class);
    }

    // /**
    //  * @return Tipe[] Returns an array of Tipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tipe
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByTypeLoc($libelle)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM tipe,bien
            WHERE tipe.id=bien.tipe_id
            AND tipe.libelle=:libelle
            ';

            $stmt = $conn->prepare($sql);
            $stmt->execute(['libelle' => $libelle]);

            // returns an array of arrays (i.e. a raw data set)
            return $stmt->fetchAll();

    }
}
