<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    private ProjectRepository $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/projects', name: 'projects')]
    public function index(): Response
    {
        $projects = $this->repository->findAll();

        return $this->render('project/list.html.twig', ['projects' => $projects]);
    }
}
