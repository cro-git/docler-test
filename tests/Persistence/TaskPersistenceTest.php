<?php


use App\Domain\TaskList\Models\Task\Task;
use App\Domain\TaskList\Models\Task\TaskDescription;
use App\Domain\TaskList\Models\Task\TaskDueDate;
use App\Domain\TaskList\Models\Task\TaskStatus;
use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserName;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskListPersistenceTest extends TestCase
{
    use DatabaseTransactions;

    public function testPersistency()
    {
        // Fror the test to be effective we need to make sure we don't already have this records in the db
        // Yes, of course we could count on the DatabaseTransactions and a goog test build with special db etc, but better to be sure
        $this->assertDatabaseMissing('tl_tasks',['description' => 'Job 1']);
        $this->assertDatabaseMissing('tl_tasks',['description' => 'Job 2']);

        $u1 = User::create(new UserName('Mario','Rossi'));
        $tl1 = TaskList::create($u1->getId(),new TaskListName('Work'));

        // We create a task and we expect the db to have this task listed
        $t1 = Task::create(new TaskDescription('Job 1'),$tl1->getId());
        $this->assertDatabaseHas('tl_tasks', ['id'=> (string)$t1->getId(), 'description' => 'Job 1','task_list_id' => (string)$tl1->getId()]);

        // Description update
        $t1->setDescription(new TaskDescription('Job 2'));
        $this->assertDatabaseMissing('tl_tasks',['description' => 'Work']);
        $this->assertDatabaseHas('tl_tasks', ['id'=> (string)$t1->getId(), 'description' => 'Job 2','task_list_id' => (string)$tl1->getId()]);

        // Status update
        $this->assertDatabaseHas('tl_tasks', ['id'=> (string)$t1->getId(), 'status' => TaskStatus::TODO]);
        $t1->setAsDone();
        $this->assertDatabaseMissing('tl_tasks',['status' => TaskStatus::TODO]);
        $this->assertDatabaseHas('tl_tasks', ['id'=> (string)$t1->getId(), 'status' => TaskStatus::DONE]);
        $t1->setAsTodo();
        $this->assertDatabaseMissing('tl_tasks',['status' => TaskStatus::DONE]);
        $this->assertDatabaseHas('tl_tasks', ['id'=> (string)$t1->getId(), 'status' => TaskStatus::TODO]);

        // Due date update
        $this->assertDatabaseHas('tl_tasks', ['id'=> (string)$t1->getId(), 'due_date' => new DateTime()]);
        $newDate = (new DateTime())->add(new DateInterval('P1D'));
        $t1->changeDate(new TaskDueDate(($newDate)));
        $this->assertDatabaseMissing('tl_tasks',['due_date' => new DateTime()]);
        $this->assertDatabaseHas('tl_tasks', ['id'=> (string)$t1->getId(), 'due_date' => $newDate]);

        // Test delete
        $t1->delete();
        $this->assertDatabaseMissing('tl_tasks',['id'=> (string)$t1->getId()]);
    }

}
