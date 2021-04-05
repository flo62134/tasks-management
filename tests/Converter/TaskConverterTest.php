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

    /**
     * @covers \App\Converter\TaskConverter::toDuration
     */
    public function testToDurationHugeValue(): void
    {
        $task = new Task();
        $task->setStartDate(new \DateTime('2021-01-01T12:00:00'));
        $task->setEndDate(new \DateTime('2022-02-02T12:00:00'));

        $expected = new \DateInterval('P1Y1M1DT0S');
        $actual = $this->taskConverter->toDuration([$task]);

        static::assertEquals($expected, $actual);
    }

    /**
     * @covers \App\Converter\TaskConverter::toDuration
     */
    public function testToDurationManyTasks(): void
    {
        $tasks = [
            (new Task())
                ->setStartDate(new \DateTime('2021-01-01T12:00:00'))
                ->setEndDate(new \DateTime('2021-01-01T14:00:00')),
            (new Task())
                ->setStartDate(new \DateTime('2021-01-01T12:00:00'))
                ->setEndDate(new \DateTime('2021-01-01T18:00:00')),
            (new Task())
                ->setStartDate(new \DateTime('2021-01-01T12:00:00'))
                ->setEndDate(new \DateTime('2021-01-01T18:00:00')),
            (new Task())
                ->setStartDate(new \DateTime('2021-01-01T12:00:00'))
                ->setEndDate(new \DateTime('2021-01-01T18:00:00')),
        ];

        $expected = new \DateInterval('PT20H');
        $actual = $this->taskConverter->toDuration($tasks);

        static::assertEquals($expected, $actual);
    }
}
