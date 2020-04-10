<?php

namespace Tests\Feature;

use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Models\User\UserName;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;


    public function testCreateUser()
    {
        // We need to check if the reply contains all the needed data, and if the record is correctly added to the db
        $response = $this->post(route('users.create'),['name' => 'Mario', 'surname' => 'Rossi']);
        $response->assertStatus(200)->assertJson(['name' => 'Mario','surname' => 'Rossi']);
        $this->assertDatabaseHas('tl_users',['name' => 'Mario','surname' => 'Rossi']);

        // Need to check if the fields are really required
        $response = $this->post(route('users.create'),['name' => 'Mario',]);
        $response->assertStatus(422)->assertJson(['errors' => ['surname' => []]]);

        $response = $this->post(route('users.create'),['surname' => 'Rossi',]);
        $response->assertStatus(422)->assertJson(['errors' => ['name' => []]]);

        $response = $this->post(route('users.create'),[]);
        $response->assertStatus(422)->assertJson(['errors' => ['name' => [],'surname' => []]]);
    }

    public function testGetUser()
    {
        $response = $this->get(route('users.list'));
        $response->assertJson([],true)->assertStatus(200);

        $u1 = User::create(new UserName('Mario','Rossi'));
        $u2 = User::create(new UserName('Gino','Pilotino'));

        $response = $this->get(route('users.list'));
        $response->assertJson([
            ['id' => $u1->getId()->getValue(),'name' => 'Mario','surname' => 'Rossi'],
            ['id' => $u2->getId()->getValue(),'name' => 'Gino','surname' => 'Pilotino']
        ],true)->assertStatus(200);

        $this->post(route('users.create'),['name' => 'Pippo', 'surname' => 'Pluto']);

        $response = $this->get(route('users.list'));
        $response->assertJson([
            ['name' => 'Mario'],
            ['name' => 'Gino'],
            ['name' => 'Pippo']
        ],true);
    }

    public function testUpdateUser()
    {
        $u1 = User::create(new UserName('Mario','Rossi'));

        $response = $this->put(route('users.update',['id' => (string)$u1->getId()]),['name' => 'Gino','surname' => 'Pilotino']);
        $response->assertStatus(200)->assertJson(['id' => $u1->getId(),'name' => 'Gino','surname' => 'Pilotino']);
        $this->assertDatabaseHas('tl_users',['id'=> $u1->getId(),'name' => 'Gino','surname' => 'Pilotino']);

        $response = $this->put(route('users.update',['id' => (string)$u1->getId()]),['name' => 'Gino']);
        $response->assertStatus(422)->assertJson(['errors' => ['surname' => []]]);

        $response = $this->put(route('users.update',['id' => (string)$u1->getId()]),['surname' => 'Gino']);
        $response->assertStatus(422)->assertJson(['errors' => ['name' => []]]);

        // Invalid UserID
        $response = $this->put(route('users.update',['id' => 'wroing']),['name' => 'Gino','surname' => 'Pilotino']);
        $response->assertStatus(404)->assertJson(['error' => 'Not a valid UserID']);

        // Valid UserID, but no record found
        $response = $this->put(route('users.update',['id' => (string)UserId::generate()]),['name' => 'Gino','surname' => 'Pilotino']);
        $response->assertStatus(404);
    }

    public function testDetailUser()
    {
        $u1 = User::create(new UserName('Mario','Rossi'));
        $u2 = User::create(new UserName('Gino','Pilotino'));

        $response = $this->get(route('users.detail',['id' => (string)$u1->getId()]));
        $response->assertStatus(200)->assertJson(['id' => (string)$u1->getId(),'name' => 'Mario','surname' => 'Rossi']);

        $response = $this->get(route('users.detail',['id' => (string)$u2->getId()]));
        $response->assertStatus(200)->assertJson(['id' => (string)$u2->getId(),'name' => 'Gino','surname' => 'Pilotino']);

        // Valid UserID, but no record found
        $response = $this->get(route('users.detail',['id' => (string)UserId::generate()]));
        $response->assertStatus(404);

        $response = $this->get(route('users.detail',['id' => 'wrong']));
        $response->assertStatus(404);
    }

    public function testDeleteUser()
    {
        $u1 = User::create(new UserName('Mario','Rossi'));

        // The record exists before the delete
        $this->assertDatabaseHas('tl_users',['id'=> $u1->getId()]);

        $response = $this->delete(route('users.delete',['id' => (string)$u1->getId()]));
        $response->assertStatus(200)->assertJson(['id' => $u1->getId()]);

        // After the delete the record is now missing, hurray!
        $this->assertDatabaseMissing('tl_users',['id' => $u1->getId()]);

        // We try again, we should not be able to delete an user twice
        $response = $this->delete(route('users.delete',['id' => (string)$u1->getId()]));
        $response->assertStatus(404);

        // Invalid UserID
        $response = $this->delete(route('users.delete',['id' => 'wrong']));
        $response->assertStatus(404)->assertJson(['error' => 'Not a valid UserID']);

        // Valid UserID, but no record found
        $response = $this->delete(route('users.delete',['id' => (string)UserId::generate()]));
        $response->assertStatus(404);
    }
}
