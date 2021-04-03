<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\Type\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/projects/{id}/tasks', name: 'tasks-index')]
    public function index(Project $project): Response
    {
        return $this->render(
            'task/list.html.twig',
            ['tasks' => $project->getTasks(), 'project' => $project]
        );
    }

    #[Route('/projects/{id}/tasks/create', name: 'tasks-create')]
    public function create(Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $task->setIsBilled(false);
        $task->setProject($project);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('tasks-create', ['id' => $project->getId()]);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }
}
