<?php


namespace Tests\Domain;


use App\Domain\Task;
use App\Domain\Task\TaskDescription;
use App\Domain\Task\TaskDueDate;
use App\Domain\Task\TaskId;
use App\Domain\Task\TaskStatus;
use App\Domain\TaskList;
use App\Domain\TaskList\TaskListId;
use App\Domain\TaskList\TaskListName;
use App\Domain\User;
use App\Domain\User\UserId;
use App\Domain\User\UserName;
use ArrayIterator;
use DateInterval;
use DateTime;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    /**
     * Testing the domain class TaskStatus
     */
    public function testTaskList()
    {
        $u1 = $this->createUser(new UserName('Mario','Rossi'));

        $l1 = new TaskList(TaskListId::generate(), $u1->getId(), new TaskListName('Home Works'), new ArrayIterator());
        $l2 = new TaskList(TaskListId::generate(), $u1->getId(), new TaskListName('Morning Works'), new ArrayIterator());
        $l3 = new TaskList(TaskListId::fromString($l1->getId()->getValue()), $u1->getId(), new TaskListName('Download List'), new ArrayIterator());

        // Check the equals operator
        $this->assertFalse($l1->equals($l2));
        $this->assertTrue($l1->equals($l3));

        // Check the name of the list
        $this->assertEquals('Home Works',(string)$l1->getName());
        $this->assertEquals('Morning Works',(string)$l2->getName());

        // Check if the list is empty
        $this->assertFalse($l1->hasTask());
        $this->assertFalse($l1->hasTaskToDo());
        $this->assertFalse($l2->hasTask());
        $this->assertFalse($l2->hasTaskToDo());

        // It's time to add some tasks and check if everything works
        $l1->addTask($this->createTask('Task 1',TaskStatus::TODO));
        $l1->addTask($this->createTask('Task 2',TaskStatus::TODO));
        $l1->addTask($this->createTask('Task 3',TaskStatus::DONE));

        // Check again, it should not be empty
        $this->assertTrue($l1->hasTask());
        $this->assertTrue($l1->hasTaskToDo());
        $this->assertFalse($l2->hasTask());
        $this->assertFalse($l2->hasTaskToDo());

        // Ok, we need to check if the TaskToDo only check for the right kind of task
        $l2->addTask($this->createTask('Task 4',TaskStatus::DONE));
        $this->assertTrue($l2->hasTask());
        $this->assertFalse($l2->hasTaskToDo());

        // Then we check if we can get the today task
        $this->assertEquals(3,$l1->getTodayTasks()->count());
        $this->assertEquals(1,$l2->getTodayTasks()->count());

        $done = new TaskStatus(TaskStatus::DONE);
        $todo = new TaskStatus(TaskStatus::TODO);

        // We can check if the filter is good
        $this->assertEquals(1,$l1->getTodayTasks($done)->count());
        $this->assertEquals(2,$l1->getTodayTasks($todo)->count());
        $this->assertEquals(1,$l2->getTodayTasks($done)->count());
        $this->assertEquals(0,$l2->getTodayTasks($todo)->count());
        $this->assertEquals(0,$l3->getTodayTasks($done)->count());
        $this->assertEquals(0,$l3->getTodayTasks($todo)->count());

        // We need to check if it really filters only 'today' task
        $tomorrow = (new DateTime())->add(new DateInterval('P1D'));
        $l2->addTask($this->createTask('Task 5',TaskStatus::DONE,$tomorrow));
        $l2->addTask($this->createTask('Task 5',TaskStatus::TODO,$tomorrow));
        $this->assertEquals(1,$l2->getTodayTasks()->count());
    }

    private function createUser(UserName $name)
    {
        return new User(
            UserId::generate(),
            $name
        );
    }

    private function createTask(string $name, int $status,$date = null)
    {
        if ($date === null)
            $date = new DateTime();

        $task = new Task(
            TaskId::generate(),
            new TaskDescription($name),
            new TaskStatus($status),
            new TaskDueDate($date)
        );
        return $task;
    }


}
