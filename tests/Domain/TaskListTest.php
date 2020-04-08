<?php


namespace Tests\Domain;


use App\Domain\TaskList\Models\Task\Task;
use App\Domain\TaskList\Models\Task\TaskDescription;
use App\Domain\TaskList\Models\Task\TaskStatus;
use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserName;
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
        $u1 = User::create(new UserName('Mario','Rossi'));

        $l1 = new TaskList(TaskListId::generate(), $u1->getId(), new TaskListName('Home Works'));
        $l2 = new TaskList(TaskListId::generate(), $u1->getId(), new TaskListName('Morning Works'));
        $l3 = new TaskList(TaskListId::fromString($l1->getId()->getValue()), $u1->getId(), new TaskListName('Download List'));

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
        Task::create(new TaskDescription('Task 1'),$l1->getId(),null,new TaskStatus(TaskStatus::TODO));
        Task::create(new TaskDescription('Task 2'),$l1->getId(),null,new TaskStatus(TaskStatus::TODO));
        Task::create(new TaskDescription('Task 3'),$l1->getId(),null,new TaskStatus(TaskStatus::DONE));

        // Check again, it should not be empty
        $this->assertTrue($l1->hasTask());
        $this->assertTrue($l1->hasTaskToDo());
        $this->assertFalse($l2->hasTask());
        $this->assertFalse($l2->hasTaskToDo());

        // Ok, we need to check if the TaskToDo only check for the right kind of task
        Task::create(new TaskDescription('Task 4'),$l2->getId(),new TaskStatus(TaskStatus::DONE));

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
        Task::create(new TaskDescription('Task 5'),$l2->getId(),$tomorrow,new TaskStatus(TaskStatus::DONE));
        Task::create(new TaskDescription('Task 6'),$l2->getId(),$tomorrow,new TaskStatus(TaskStatus::TODO));

        $this->assertEquals(1,$l2->getTodayTasks()->count());
    }
}
