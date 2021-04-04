<?php

declare(strict_types=1);

namespace App\Controller;

use App\Converter\TaskConverter;
use App\Form\Type\StatsType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    private TaskRepository $taskRepository;
    private ProjectRepository $projectRepository;
    private TaskConverter $taskConverter;

    public function __construct(TaskRepository $taskRepository, ProjectRepository $projectRepository, TaskConverter $taskConverter)
    {
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
        $this->taskConverter = $taskConverter;
    }

    #[Route('/stats', name: 'stats-dashboard')]
    public function dashboard(Request $request): Response
    {
        $form = $this->createForm(StatsType::class);
        $form->handleRequest($request);

        $formData = $form->getData();
        $project = $formData['project'] ?? null;
        $from = $formData['from'] ?? null;
        $to = $formData['to'] ?? null;

        $tasks = $this->taskRepository->findByProjectAndPeriod($project, $from, $to);
        $totalDuration = $this->taskConverter->toDuration($tasks->toArray());
        $days = $from && $to ? $from->diff($to)->days + 1 : null;

        $params = [
            'tasksCount' => $tasks->count(),
            'totalDuration' => $totalDuration,
            'days' => $days,
            'projects' => $this->projectRepository->findAll(),
            'form' => $form->createView(),
        ];

        return $this->render('stats/dashboard.html.twig', $params);
    }
}
