<?php

namespace App\Controller;

use App\Repository\AccountSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActivityController extends AbstractController
{

  public function __construct(
    private EntityManagerInterface $entityManager,
    private AccountSessionRepository $sessionRepository,
  ) {}

  #[Route('/admin/activities', name: 'admin_activities')]
  public function activities(): Response
  {
    $sessions = $this->sessionRepository->findAll();

    return $this->render('activities.twig', [
      'session' => $sessions
    ]);
  }
}
