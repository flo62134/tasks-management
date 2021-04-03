<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks/create', name: 'tasks-create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $task->setIsBilled(false);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('tasks-create');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }
}
