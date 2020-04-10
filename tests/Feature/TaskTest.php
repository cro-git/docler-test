<?php

namespace Tests\Feature;

use App\Domain\TaskList\Models\Task\Task;
use App\Domain\TaskList\Models\Task\TaskDescription;
use App\Domain\TaskList\Models\Task\TaskId;
use App\Domain\TaskList\Models\Task\TaskStatus;
use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserName;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetLists()
    {
        // Check with a wrong id
        $response = $this->get(route('task.list',['task_list_id' => 'wrong']));
        $response->assertJson([],true)->assertStatus(404);

        $response = $this->get(route('task.list',['task_list_id' => (string)TaskListId::generate()]));
        $response->assertJson([],true)->assertStatus(404);

        // Let's create some meaningful data and check if it works
        $u1 = User::create(new UserName('Mario','Rossi'));
        $tl1 = TaskList::create($u1->getId(),new TaskListName('List 1'));

        // At the beginning, it's an empty list..
        $response = $this->get(route('task.list',['task_list_id' => (string)$tl1->getId()]));
        $response->assertJson([],true)->assertStatus(200);

        $t1 = Task::create(new TaskDescription('Job 1'),$tl1->getId());
        $t2 = Task::create(new TaskDescription('Job 2'),$tl1->getId());

        $tl2 = TaskList::create($u1->getId(),new TaskListName('List 2'));
        $t3 = Task::create(new TaskDescription('Job 3'),$tl2->getId());

        $response = $this->get(route('task.list',['task_list_id' => (string)$tl1->getId()]));
        $response->assertJson([
            ['id' => (string)$t1->getId(),'description' => 'Job 1'],
            ['id' => (string)$t2->getId(),'description' => 'Job 2']
        ],true)->assertStatus(200);
        $response->assertJsonMissing([['id' => $t3->getId()]]);

        $response = $this->get(route('task.list',['task_list_id' => (string)$tl2->getId()]));
        $response->assertJson([
            ['id' => (string)$t3->getId(),'description' => 'Job 3'],
        ],true)->assertStatus(200);
        $response->assertJsonMissing([['id' => $t1->getId()]]);
    }

    public function testCreateTask()
    {
        // We need to check if the reply contains all the needed data, and if the record is correctly added to the db
        $ut = User::create(new UserName('Mario', 'Rossi'));
        $tl1 = TaskList::create($ut->getId(), new TaskListName('List 1'));


        // Base parameter
        $response = $this->post(
            route('task.create'),
            ['task_list_id' => (string)$tl1->getId(), 'description' => 'Job 1']
        );
        $response->assertStatus(200)->assertJson(['description' => 'Job 1']);
        $this->assertDatabaseHas('tl_tasks', ['description' => 'Job 1', 'task_list_id' => (string)$tl1->getId()]);

        $response = $this->post(
            route('task.create'),
            ['task_list_id' => (string)$tl1->getId(), 'description' => 'Job 2']
        );
        $response->assertStatus(200)->assertJson(['description' => 'Job 2']);
        $this->assertDatabaseHas('tl_tasks', ['description' => 'Job 2', 'task_list_id' => (string)$tl1->getId()]);

        // Test the status parameters
        $response = $this->post(
            route('task.create'),
            ['task_list_id' => (string)$tl1->getId(), 'description' => 'Job 3', 'status' => TaskStatus::DONE]
        );
        $response->assertStatus(200)->assertJson(['description' => 'Job 3', 'status' => 'Done']);
        $response = $this->post(
            route('task.create'),
            ['task_list_id' => (string)$tl1->getId(), 'description' => 'Job 4', 'status' => TaskStatus::TODO]
        );
        $response->assertStatus(200)->assertJson(['description' => 'Job 4', 'status' => 'Todo']);
        $response = $this->post(
            route('task.create'),
            ['task_list_id' => (string)$tl1->getId(), 'description' => 'Job 5', 'status' => 437287]
        );
        $response->assertStatus(422)->assertJson(['errors' => ['status' => []]]);

        // Test the due date parameters
        $response = $this->post(
            route('task.create'),
            ['task_list_id' => (string)$tl1->getId(), 'description' => 'Job 6', 'due_date' => '20-11-2000']
        );
        $response->assertStatus(200)->assertJson(['description' => 'Job 6', 'due_date' => '20-11-2000']);

        $response = $this->post(
            route('task.create'),
            ['task_list_id' => (string)$tl1->getId(), 'description' => 'Job 7', 'due_date' => '20/11/2000']
        );
        $response->assertStatus(422)->assertJson(['errors' => ['due_date' => []]]);

        // Need to check if the fields are really required
        $response = $this->post(route('task.create'), ['task_list_id' => (string)$tl1->getId()]);
        $response->assertStatus(422)->assertJson(['errors' => ['description' => []]]);

        $response = $this->post(route('task.create'), ['description' => 'Job 8']);
        $response->assertStatus(422)->assertJson(['errors' => ['task_list_id' => []]]);

        $response = $this->post(route('task.create'), []);
        $response->assertStatus(422)->assertJson(['errors' => ['task_list_id' => [], 'description' => []]]);
    }

    public function testTaskDetail()
    {
        $ut = User::create(new UserName('Mario','Rossi'));
        $tl = TaskList::create($ut->getId(),new TaskListName('List 1'));

        $t1 = Task::create(new TaskDescription('Job 1'),$tl->getId());
        $t2 = Task::create(new TaskDescription('Job 2'),$tl->getId());

        $response = $this->get(route('task.detail',['id' => (string)$t1->getId()]));
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId(),'description' => 'Job 1']);

        $response = $this->get(route('task.detail',['id' => (string)$t2->getId()]));
        $response->assertStatus(200)->assertJson(['id' => (string)$t2->getId(),'description' => 'Job 2']);

        $response = $this->get(route('task.detail',['id' => 'wrong']));
        $response->assertStatus(404);

        $response = $this->get(route('task.detail',['id' => TaskId::generate()]));
        $response->assertStatus(404);
    }

    public function testTaskUpdate()
    {
        // Check with some wrong id
        $response = $this->put(route('task.update',['id' => 'wrong']));
        $response->assertStatus(404);

        $response = $this->put(route('task.update',['id' => TaskId::generate()]));
        $response->assertStatus(404);

        $ut = User::create(new UserName('Mario','Rossi'));
        $tl = TaskList::create($ut->getId(),new TaskListName('List 1'));
        $t1 = Task::create(new TaskDescription('Job 1'),$tl->getId());

        // Update the description
        $response = $this->put(route('task.update',['id' => $t1->getId()]),['description' => 'Job 2']);
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId(),'description' => 'Job 2']);
        $this->assertDatabaseHas('tl_tasks',['id'=> $t1->getId(),'description' => 'Job 2']);
        $this->assertDatabaseMissing('tl_tasks',['description' => 'Job 1']);

        // Update the status
        $response = $this->put(route('task.update',['id' => $t1->getId()]),['status' => TaskStatus::DONE]);
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId(),'status' => 'Done']);

        $response = $this->put(route('task.update',['id' => $t1->getId()]),['status' => TaskStatus::TODO]);
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId(),'status' => 'Todo']);


        // Update the date
        $response = $this->put(route('task.update',['id' => $t1->getId()]),['due_date' => '10-01-2020']);
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId(),'due_date' => '10-01-2020']);

        $response = $this->put(route('task.update',['id' => $t1->getId()]),['due_date' => '10-01-2021']);
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId(),'due_date' => '10-01-2021']);


        // We don't have mandatory fields, check if it's right..
        $response = $this->put(route('task.update',['id' => $t1->getId()]),[]);
        $response->assertStatus(200)->assertJsonMissing(['errors' => []]);
    }

    public function testDelete()
    {
        $ut = User::create(new UserName('Mario','Rossi'));
        $tl = TaskList::create($ut->getId(),new TaskListName('List 1'));
        $t1 = Task::create(new TaskDescription('Job 1'),$tl->getId());

        // We have the task before the delete, and then it disappers
        $this->assertDatabaseHas('tl_tasks',['id'=> (string)$t1->getId()]);
        $response = $this->delete(route('task.delete',['id' => (string)$t1->getId()]));
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId()]);
        $this->assertDatabaseMissing('tl_tasks',['id'=> (string)$t1->getId()]);

        // We cannot delete a record twice
        $response = $this->delete(route('task.delete',['id' => (string)$t1->getId()]));
        $response->assertStatus(404);

        // And of course, we cannot delete a wrong id
        $response = $this->delete(route('task.delete',['id'=> 'wrong']));
        $response->assertStatus(404);

        $response = $this->delete(route('task.delete',['id'=> (string)TaskId::generate()]));
        $response->assertStatus(404);
    }

}
