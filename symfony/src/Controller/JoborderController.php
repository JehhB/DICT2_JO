<?php

namespace App\Controller;

use App\Repository\JobOrderRepository;
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
    /** @var \App\Entity\Account */
    $account = $this->getUser();

    $search = $request->query->getString('search', '');

    $qb = $this->jobOrderRepository->createJoinedQueryBuilder();
    $qb = $qb->orWhere('joborder.client_name LIKE :search')
      ->orWhere('joborder.control_number LIKE :search');

    if ($account->isAdmin()) {
      $qb = $qb->orWhere('performer.name LIKE :search');
    }
    $qb = $qb->setParameter(':search', $search);

    if (!is_null($account->getPersonnel())) {
      $qb = $qb->andWhere('joborder.performer = :performer')
        ->setParameter(':performer', $account);
    }

    $jobOrders = $paginator->paginate(
      $qb,
      $request->query->getInt('page', 1),
      10
    );

    return $this->render('job_orders.twig', [
      'jobOrders' => $jobOrders,
      'search' => $search,
    ]);
  }
}
