<?php

namespace App\Controller;

use App\Repository\AccountSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActivityController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private AccountSessionRepository $sessionRepository,
  ) {}

  #[Route('/admin/activities', name: 'activities_index', methods: ['GET'])]
  public function activities(PaginatorInterface $paginator, Request $request): Response
  {
    $qb = $this->sessionRepository->createJoinedQueryBuilder()
      ->addOrderBy('session.login_at', 'DESC');

    $sessions = $paginator->paginate(
      $qb,
      $request->query->getInt('page', 1),
      10,
    );

    return $this->render('activities.twig', [
      'sessions' => $sessions
    ]);
  }

  #[Route('/admin/activities', name: 'activities_logout', methods: ['DELETE'])]
  public function logoutAll()
  {
    $this->sessionRepository->logoutAll();
    return $this->redirectToRoute('activities_index');
  }
}
