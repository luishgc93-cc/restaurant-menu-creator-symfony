<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Informacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method Informacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Informacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Informacion[]    findAll()
 * @method Informacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class InformationRepository extends ServiceEntityRepository
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
