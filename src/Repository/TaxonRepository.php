<?php

namespace App\Repository;

use App\Entity\Taxon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Taxon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taxon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taxon[]    findAll()
 * @method Taxon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taxon::class);
    }

    /**
	 * 
	 * 
	 * findByCommonName
	 * 
	 * @param string $orderBy
     * @return Taxon[] Returns an array of Taxon objects
     */
    public function findByCommonName($orderBy='ASC')
    {
        return $this->createQueryBuilder('t')
            //->andWhere('t.commonName = :val')
            //->setParameter('val', $value)
            ->orderBy('t.commonName', $orderBy)
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
	 * 
	 * findByGenericName
	 *  
	 * @param string $orderBy
     * @return Taxon[] Returns an array of Taxon objects
     */
    public function findByGenericName($orderBy='ASC')
    {
        return $this->createQueryBuilder('t')
            //->andWhere('t.genericName = :val')
            //->setParameter('val', $value)
            ->orderBy('t.genericName', $orderBy)
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
	 * findBySpecificName
	 *  
	 * @param string $orderBy
     * @return Taxon[] Returns an array of Taxon objects
     */
    public function findByFamily($orderBy='ASC')
    {
        return $this->createQueryBuilder('t')
            //->andWhere('t.specificName = :val')
            //->setParameter('val', $value)
            ->orderBy('t.family', $orderBy)
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Taxon
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
