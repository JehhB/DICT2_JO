<?php

namespace App\Controller;

use App\Repository\JobOrderRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JoborderController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private JobOrderRepository $jobOrderRepository
  ) {}

  #[Route('/admin/job_orders', name: 'admin_joborders')]
  #[Route('/dashboard', name: 'user_dashboard')]
  public function index(PaginatorInterface $paginator, Request $request): Response
  {
    $qb = $this->jobOrderRepository->createJoinedQueryBuilder();

    $criteria = Criteria::create();

    /** @var \App\Entity\Account */
    $account = $this->getUser();
    if (!is_null($account->getPersonnel())) {
      $criteria->andWhere(Criteria::expr()->eq('joborder.performer', $account->getPersonnel()));
    }

    $qb->addCriteria($criteria);
    $jobOrders = $paginator->paginate(
      $qb,
      $request->request->getInt('p', 1),
      10
    );

    return $this->render('dashboard.twig', [
      'jobOrders' => $jobOrders,
    ]);
  }
}
