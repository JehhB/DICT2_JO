<?php

namespace App\Controller;

use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonnelController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private PersonnelRepository $personnelRepository,
    private ProjectRepository $projectRepository
  ) {}

  #[Route('/admin/personnels', name: 'personnels_index', methods: ['GET'])]
  public function index(): Response
  {
    $personnels = $this->personnelRepository->findAllJoined();
    $projects = $this->projectRepository->findAll();

    return $this->render('admin/personnels.twig', [
      'personnel' => $personnels,
      'projects' => $projects,
    ]);
  }

  #[Route('/admin/personnels', name: 'personnels_add', methods: ['POST'])]
  public function add(Request $request): Response
  {
    $projects = $this->projectRepository->find($request->request->get('project_id'));

    if (is_null($projects)) {
      return new Response("Project does not exist.", 400);
    }

    $personnel = new Personnel();
    $personnel->setName($request->request->get('name'));
    $personnel->setPosition($request->request->get('position'));
    $personnel->setProject($projects);

    $this->entityManager->persist($personnel);
    $this->entityManager->flush();

    $this->addFlash('success', 'Personnel created successfully.');
    return $this->redirectToRoute('personnels_index');
  }

  #[Route('/admin/personnels/{id}', name: 'personnel_edit', methods: ['PUT'])]
  public function edit(Personnel $personnel, Request $request)
  {
    if ($request->request->has('name')) $personnel->setName($request->request->get('name'));
    if ($request->request->has('position')) $personnel->setPosition($request->request->get('position'));

    if (
      $request->request->has('project_id') && !is_null(
        $project = $this->projectRepository->find($request->request->get('project_id'))
      )
    ) {
      $personnel->setProject($project);
    }
    $this->entityManager->flush();

    return $this->redirectToRoute('personnels_index');
  }

  #[Route('/admin/personnels/{id}', name: 'personnel_delete', methods: ['DELETE'])]
  public function delete(Personnel $personnel): Response
  {
    $personnel->setDeleted(true);
    $this->entityManager->flush();
    return $this->redirectToRoute('personnels_index');
  }
}
