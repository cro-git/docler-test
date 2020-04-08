<?php


namespace Tests\Domain;


use App\Domain\Base\UuidType;
use App\Domain\Task;
use App\Domain\Task\TaskDescription;
use App\Domain\Task\TaskDueDate;
use App\Domain\Task\TaskId;
use App\Domain\Task\TaskStatus;
use DateInterval;
use DateTime;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * Testing the domain class TaskStatus
     */
    public function testStatus()
    {
        $s1 = new TaskStatus(TaskStatus::DONE);
        $s2 = TaskStatus::create(TaskStatus::DONE);
        $s3 = new TaskStatus(TaskStatus::TODO);

        // Check the equals operator
        $this->assertTrue($s1->equals($s2));
        $this->assertFalse($s1->equals($s3));

        // Check the getValue to be sure it set and get the right value
        $this->assertEquals(TaskStatus::DONE,$s1->getValue());
        $this->assertEquals(TaskStatus::DONE,$s2->getValue());
        $this->assertEquals(TaskStatus::TODO,$s3->getValue());

        // Check the isDone method
        $this->assertTrue($s1->isDone());
        $this->assertFalse($s3->isDone());
    }

    public function testTask()
    {
        $t1 = new Task(
            TaskId::generate(),
            new TaskDescription('test'),
            TaskStatus::create(TaskStatus::TODO),
            new TaskDueDate(new DateTime())
        );
        $t2 = new Task(
            TaskId::generate(),
            new TaskDescription('test2'),
            TaskStatus::create(TaskStatus::TODO),
            new TaskDueDate(new DateTime())
        );
        $t3 = new Task(
            TaskId::fromString($t1->getId()->getValue()),
            new TaskDescription('test3'),
            TaskStatus::create(TaskStatus::DONE),
            new TaskDueDate((new DateTime())->add(new DateInterval('P1D')))
        );

        // Check if the task store the id correctly
        $this->assertTrue(UuidType::isValid($t1->getId()->getValue()));

        // Check if the equals operator works
        $this->assertFalse($t1->equals($t2));
        $this->assertTrue($t1->equals($t3));

        // Check the description
        $this->assertEquals('test',(string)$t1->getDescription());
        $this->assertEquals('test2',(string)$t2->getDescription());

        // Check the status
        $this->assertFalse($t1->isDone());
        $this->assertTrue($t3->isDone());

        // Check the date
        $this->assertTrue($t1->isDueToday());
        $this->assertFalse($t3->isDueToday());

    }

}
