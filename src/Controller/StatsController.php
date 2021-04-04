<?php

declare(strict_types=1);

namespace App\Controller;

use App\Converter\TaskConverter;
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
        $project = $request->get('project') ? $this->projectRepository->find($request->get('project')) : null;
        $from = $request->get('from') ? new \DateTime($request->get('from')) : null;
        $to = $request->get('to') ? new \DateTime($request->get('to')) : null;

        $tasks = $this->taskRepository->findByProjectAndPeriod($project, $from, $to);
        $totalDuration = $this->taskConverter->toDuration($tasks);
        $days = $from && $to ? $from->diff($to)->days + 1 : null;

        $stats = [
            'tasksCount' => $tasks->count(),
            'totalDuration' => $totalDuration,
            'days' => $days,
        ];

        return $this->render('stats/dashboard.html.twig', $stats);
    }
}
