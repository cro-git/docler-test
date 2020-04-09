<?php


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
        $this->assertDatabaseMissing('tl_task_lists',['name' => 'Work']);
        $this->assertDatabaseMissing('tl_task_lists',['name' => 'Sport']);

        $u1 = User::create(new UserName('Mario','Rossi'));
//
        // We create a task list and we expect the db to have this record
        $t1 = TaskList::create($u1->getId(),new TaskListName('Work'));
        $this->assertDatabaseHas('tl_task_lists', ['id'=> (string)$t1->getId(), 'name' => 'Work','user_id' => (string)$u1->getId()]);

        $t1->changeName(new TaskListName('Sport'));
        // The old name disappears, and the new name appear
        $this->assertDatabaseMissing('tl_task_lists',['name' => 'Work']);
        $this->assertDatabaseHas('tl_task_lists', ['id'=> (string)$t1->getId(), 'name' => 'Sport','user_id' => (string)$u1->getId()]);

        // Test delete
        $t1->delete();
        $this->assertDatabaseMissing('tl_task_lists',['id'=> (string)$t1->getId()]);
    }

}
