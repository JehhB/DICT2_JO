<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FrontController extends AbstractController
{
  #[Route('/', name: 'app_front')]
  public function index(): Response
  {
    /** @var ?\App\Entity\Account */
    $account = $this->getUser();
    if (is_null($account)) return $this->redirectToRoute('app_login');
    if ($account->isAdmin()) return $this->redirectToRoute('admin_joborders');
    return $this->redirectToRoute('user_dashboard');
  }
}
