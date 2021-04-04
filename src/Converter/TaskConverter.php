<?php

declare(strict_types=1);

namespace App\Converter;

use App\Entity\Task;
use Doctrine\Common\Collections\Collection;

class TaskConverter
{
    public function toDuration(Collection $tasks): \DateInterval
    {
        $duration = new \DateInterval('PT0S');

        foreach ($tasks as $task) {
            /** @var Task $task */
            $taskDuration = $task->getDuration();
            $duration->h += $taskDuration->h;
            $duration->i += $taskDuration->i;
        }

        return $duration;
    }
}
