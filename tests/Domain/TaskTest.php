<?php


namespace Tests\Domain;


use App\Domain\Base\UuidType;
use App\Domain\TaskList\Event\Task\TaskHasBeenCreated;
use App\Domain\TaskList\Event\Task\TaskHasBeenDeleted;
use App\Domain\TaskList\Event\Task\TaskHasBeenUpdated;
use App\Domain\TaskList\Event\User\UserHasBeenDeleted;
use App\Domain\TaskList\Models\Task\Task;
use App\Domain\TaskList\Models\Task\TaskDescription;
use App\Domain\TaskList\Models\Task\TaskDueDate;
use App\Domain\TaskList\Models\Task\TaskId;
use App\Domain\TaskList\Models\Task\TaskStatus;
use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserName;
use DateInterval;
use DateTime;
use Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

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
            new TaskDueDate(new DateTime()),
            TaskListId::generate()
        );
        $t2 = new Task(
            TaskId::generate(),
            new TaskDescription('test2'),
            TaskStatus::create(TaskStatus::TODO),
            new TaskDueDate(new DateTime()),
            TaskListId::generate()
        );
        $t3 = new Task(
            TaskId::fromString($t1->getId()->getValue()),
            new TaskDescription('test3'),
            TaskStatus::create(TaskStatus::DONE),
            new TaskDueDate((new DateTime())->add(new DateInterval('P1D'))),
            TaskListId::generate()
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

    public function testEvents()
    {
        // We lister for the possible dispatched event, so we can be sure that the User is dispatching event correctly
        Event::fake([
            TaskHasBeenCreated::class,
            TaskHasBeenUpdated::class,
            TaskHasBeenDeleted::class
        ]);

        $u1 = User::create(new UserName('M','R'));
        $l1 = TaskList::create($u1->getId(), new TaskListName('Home Works'));
        $t1 = Task::create(new TaskDescription('Job 1'),$l1->getId());
        Event::assertDispatched(TaskHasBeenCreated::class,function ($event) use ($t1) {
            return $t1->equals($event->task);
        });

        $t2 = Task::create(new TaskDescription('Job 2'),$l1->getId());
        Event::assertDispatched(TaskHasBeenCreated::class,function ($event) use ($t2) {
            return $t2->equals($event->task);
        });

        Event::assertNotDispatched(TaskHasBeenUpdated::class);



        new Task(
            TaskId::generate(),
            new TaskDescription('test2'),
            TaskStatus::create(TaskStatus::TODO),
            new TaskDueDate(new DateTime()),
            TaskListId::generate()
        );
        Event::assertDispatched(TaskHasBeenCreated::class,2); // We didn't create the TaskList, so we should have 2 event ( for the 2 previus creation )

        // No previous "hasBeenUpdated" event fired
        Event::assertNotDispatched(TaskHasBeenUpdated::class);

        // Check if the name change correctly when we change name
        $t1->setAsTodo();
        Event::assertDispatched(TaskHasBeenUpdated::class,function ($event) use ($t1)
        {
            return $t1->equals($event->task);
        });
        $t1->setAsDone();
        Event::assertDispatched(TaskHasBeenUpdated::class,function ($event) use ($t1)
        {
            return $t1->equals($event->task);
        });

        $t1->changeDate(new TaskDueDate(new DateTime()));
        Event::assertDispatched(TaskHasBeenUpdated::class,function ($event) use ($t1)
        {
            return $t1->equals($event->task);
        });

        Event::assertNotDispatched(TaskHasBeenDeleted::class);
        $t1->delete();
        Event::assertDispatched(TaskHasBeenDeleted::class,function ($event) use ($t1)
        {
            return $t1->equals($event->task);
        });
    }

}
