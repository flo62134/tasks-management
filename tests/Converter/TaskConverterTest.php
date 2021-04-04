<?php

declare(strict_types=1);

namespace App\Tests\Converter;

use App\Converter\TaskConverter;
use App\Entity\Task;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Converter\TaskConverter
 *
 * @internal
 */
final class TaskConverterTest extends TestCase
{
    private TaskConverter $taskConverter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskConverter = new TaskConverter();
    }

    /**
     * @covers \App\Converter\TaskConverter::toDuration
     */
    public function testToDurationSingleTask(): void
    {
        $task = new Task();
        $task->setStartDate(new \DateTime('2021-01-01T12:00:00'));
        $task->setEndDate(new \DateTime('2021-01-01T14:00:00'));

        $expected = new \DateInterval('PT2H');
        $actual = $this->taskConverter->toDuration([$task]);

        static::assertEquals($expected, $actual);
    }
}
