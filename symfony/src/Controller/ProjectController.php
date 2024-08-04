<?php

namespace App\Controller;

use App\Repository\PersonnelRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private PersonnelRepository $personnelRepository,
    private ProjectRepository $projectRepository
  ) {}

  #[Route('/admin/projects', name: 'admin_projects')]
  public function projects(): Response
  {
    $projects = $this->projectRepository->findAllJoined();
    $personnels = $this->personnelRepository->findAll();

    $options = array_map(function ($item) {
      return $item->getName();
    }, $personnels);

    return $this->render('admin/projects.twig', [
      'project' => $projects,
      'personnel' => $personnels,
      'options' => $options,
    ]);
  }
}
