<?php

namespace App\Repository;

use App\Entity\JobOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobOrder>
 */
class JobOrderRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, JobOrder::class);
  }

  public function createJoinedQueryBuilder(
    string $joborder = 'joborder',
    string $project = 'project',
    string $performer = 'performer',
    string $issuer = 'issuer',
    string $approver = 'approver'
  ): QueryBuilder {
    $qb = $this->createQueryBuilder($joborder)
      ->leftJoin($joborder . '.project', $project)
      ->leftJoin($joborder . '.performer', $performer)
      ->leftJoin($joborder . '.issuer', $issuer)
      ->leftJoin($joborder . '.approver', $approver)
      ->select($joborder, $project, $performer, $issuer, $approver);

    return $qb;
  }
}
