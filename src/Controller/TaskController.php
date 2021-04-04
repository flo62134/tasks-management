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
    #[Route('/projects/{id}/tasks', name: 'tasks-list')]
    public function list(Project $project): Response
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

            return $this->redirectToRoute('tasks-list', ['id' => $project->getId()]);
        }

        return $this->render(
            'task/create.html.twig',
            ['form' => $form->createView(), 'project' => $project]
        );
    }

    #[Route('/tasks/{id}/bill', name: 'tasks-bill', methods: ['PATCH'])]
    public function bill(Task $task, EntityManagerInterface $entityManager): Response
    {
        $task->setIsBilled(true);
        $entityManager->flush();

        return $this->json($task, Response::HTTP_OK);
    }
}
