<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\Type\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    private ProjectRepository $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/projects', name: 'projects-index')]
    public function index(): Response
    {
        $projects = $this->repository->findAll();

        return $this->render('project/list.html.twig', ['projects' => $projects]);
    }

    #[Route('/projects/create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('projects-index');
        }

        return $this->render('project/create.html.twig', ['form' => $form->createView(),]);
    }
}
