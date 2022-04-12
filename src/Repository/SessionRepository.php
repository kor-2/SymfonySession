<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Session $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Session $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Recherche de session en cour.
     */
    public function enCour()
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('s')
            ->andWhere('s.debut < :today')
            ->andWhere('s.fin > :today')
            ->setParameter('today', $today)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche e session fini.
     */
    public function finSess()
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('s')
            ->andWhere('s.fin < :today')
            ->setParameter('today', $today)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche e session a venir.
     */
    public function aVenirSess()
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('s')
            ->andWhere('s.debut > :today')
            ->setParameter('today', $today)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Session[] Returns an array of Session objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Session
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
