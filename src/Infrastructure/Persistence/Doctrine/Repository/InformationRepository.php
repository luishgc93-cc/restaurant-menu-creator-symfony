<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Informacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method Informacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Informacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Informacion[]    findAll()
 * @method Informacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Informacion::class);
    }

    public function save(Informacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Informacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



}
