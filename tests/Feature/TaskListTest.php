<?php

namespace Tests\Feature;

use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Models\User\UserName;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetLists()
    {
        // Check with a wrong id
        $response = $this->get('/api/taskList/user/wrong_id');
        $response->assertJson([],true)->assertStatus(404);

        $response = $this->get('/api/taskList/user/'.UserId::generate());
        $response->assertJson([],true)->assertStatus(404);

        // Let's create some meaningful data and check if it works
        $u1 = User::create(new UserName('Mario','Rossi'));

        // At the beginning, it's an empty list..
        $response = $this->get('/api/taskList/user/'.$u1->getId());
        $response->assertJson([],true)->assertStatus(200);

        $t1 = TaskList::create($u1->getId(),new TaskListName('List 1'));
        $t2 = TaskList::create($u1->getId(),new TaskListName('List 2'));

        $u2 = User::create(new UserName('Gino','Pilotino'));
        $t3 = TaskList::create($u2->getId(),new TaskListName('List 3'));

        $response = $this->get('/api/taskList/user/'.$u1->getId());
        $response->assertJson([
            ['id' => $t1->getId()->getValue(),'name' => 'List 1'],
            ['id' => $t2->getId()->getValue(),'name' => 'List 2']
        ],true)->assertStatus(200);
        $response->assertJsonMissing([['id' => $t3->getId()]]);

        $response = $this->get('/api/taskList/user/'.$u2->getId());
        $response->assertJson([
            ['id' => $t3->getId()->getValue(),'name' => 'List 3'],
        ],true)->assertStatus(200);
        $response->assertJsonMissing([['id' => $t1->getId()]]);
    }

    public function testCreateList()
    {
        // We need to check if the reply contains all the needed data, and if the record is correctly added to the db
        $ut = User::create(new UserName('Mario','Rossi'));
        $response = $this->post('/api/taskList',['user_id' => (string)$ut->getId(),'name' => 'List1']);
        $response->assertStatus(200)->assertJson(['name' => 'List1']);
        $this->assertDatabaseHas('tl_task_lists',['name' => 'List1','user_id' => (string)$ut->getId()]);

        $response = $this->post('/api/taskList',['user_id' => (string)$ut->getId(),'name' => 'List2']);
        $response->assertStatus(200)->assertJson(['name' => 'List2']);
        $this->assertDatabaseHas('tl_task_lists',['name' => 'List2','user_id' => (string)$ut->getId()]);

        // Need to check if the fields are really required
        $response = $this->post('/api/taskList',['user_id' => 'wrong_id','name' => 'Mario']);
        $response->assertStatus(422)->assertJson(['errors' => ['user_id' => [],'user_id' => []]]);

        $response = $this->post('/api/taskList',['user_id' => UserId::generate(),'name' => 'Mario']);
        $response->assertStatus(422)->assertJson(['errors' => ['user_id' => [],'user_id' => []]]);

        $response = $this->post('/api/taskList',['name' => 'Mario',]);
        $response->assertStatus(422)->assertJson(['errors' => ['user_id' => []]]);

        $response = $this->post('/api/taskList',['user_id' => (string)$ut->getId(),]);
        $response->assertStatus(422)->assertJson(['errors' => ['name' => []]]);

        $response = $this->post('/api/taskList',[]);
        $response->assertStatus(422)->assertJson(['errors' => ['user_id' => [],'name' => []]]);
    }

    public function testTaskListDetail()
    {
        $u1 = User::create(new UserName('Mario','Rossi'));
        $t1 = TaskList::create($u1->getId(),new TaskListName('List 1'));
        $t2 = TaskList::create($u1->getId(),new TaskListName('List 2'));

        $response = $this->get('/api/taskList/'.$t1->getId());
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId(),'name' => 'List 1']);

        $response = $this->get('/api/taskList/'.$t2->getId());
        $response->assertStatus(200)->assertJson(['id' => (string)$t2->getId(),'name' => 'List 2']);

        $response = $this->get('/api/taskList/'.TaskListId::generate());
        $response->assertStatus(404);

        $response = $this->get('/api/taskList/wrong');
        $response->assertStatus(404);
    }

    public function testChangeName()
    {
        // Check with some wrong id
        $response = $this->put('/api/taskList/wrong',['name' => 'List']);
        $response->assertStatus(404);

        $response = $this->put('/api/taskList/'.TaskListId::generate(),['name' => 'List']);
        $response->assertStatus(404);

        $u1 = User::create(new UserName('Mario','Rossi'));
        $t1 = TaskList::create($u1->getId(),new TaskListName('List1'));

        // Check if it really change the value
        $response = $this->put('/api/taskList/'.$t1->getId(),['name' => 'List 2']);
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId(),'name' => 'List 2']);
        $this->assertDatabaseHas('tl_task_lists',['id'=> $t1->getId(),'name' => 'List 2']);
        $this->assertDatabaseMissing('tl_task_lists',['name' => 'List1']);

        // Check the mandatory fields
        $response = $this->put('/api/taskList/'.$t1->getId(),[]);
        $response->assertStatus(422)->assertJson(['errors' => ['name' => []]]);
    }

    public function testDeleteTaskList()
    {
        $u1 = User::create(new UserName('Mario','Rossi'));
        $t1 = TaskList::create($u1->getId(),new TaskListName('List1'));

        // We have the task before the delete, and then it disappers
        $this->assertDatabaseHas('tl_task_lists',['id'=> $t1->getId()]);
        $response = $this->delete('/api/taskList/'.$t1->getId());
        $response->assertStatus(200)->assertJson(['id' => (string)$t1->getId()]);
        $this->assertDatabaseMissing('tl_task_lists',['id'=> $t1->getId()]);

        // We cannot delete a record twice
        $response = $this->delete('/api/taskList/'.$t1->getId());
        $response->assertStatus(404);

        // And of course, we cannot delete a wrong id
        $response = $this->delete('/api/taskList/wrong');
        $response->assertStatus(404);

        $response = $this->delete('/api/taskList/'.TaskListId::generate());
        $response->assertStatus(404);
    }
}
