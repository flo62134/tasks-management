<?php

declare(strict_types=1);

namespace App\Converter;

use App\Entity\Task;

class TaskConverter
{
    /**
     * @param Task[] $tasks
     */
    public function toDuration(array $tasks): \DateInterval
    {
        $duration = new \DateInterval('PT0S');

        foreach ($tasks as $task) {
            $taskDuration = $task->getDuration();
            $duration->h += $taskDuration->h;
            $duration->i += $taskDuration->i;
        }

        return $duration;
    }
}
