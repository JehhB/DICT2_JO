<?php

namespace App\Repository;

use App\Entity\AccountSession;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccountSession>
 */
class AccountSessionRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, AccountSession::class);
  }

  public function createJoinedQueryBuilder(
    string $accountSession = 'session',
    string $account = 'account',
    string $personnel = 'personnel',
  ) {
    $qb = $this->createQueryBuilder($accountSession)
      ->leftJoin("$accountSession.account", $account)
      ->leftJoin("$account.personnel", $personnel)
      ->select($accountSession, $account, $personnel);

    return $qb;
  }

  public function logoutAll()
  {
    $em = $this->getEntityManager();
    $query = $em->createQuery(
      'UPDATE App\Entity\AccountSession s
       SET s.logout_at = :now
       WHERE s.logout_at IS NULL'
    )->setParameter(':now', new DateTimeImmutable());
    $query->execute();
  }
}
