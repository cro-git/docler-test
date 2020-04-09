<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;


    public function testCreateUser()
    {
        // We need to check if the reply contains all the needed data, and if the record is correctly added to the db
        $response = $this->post('/api/users',['name' => 'Mario', 'surname' => 'Rossi']);
        $response->assertStatus(200)->assertJson(['name' => 'Mario','surname' => 'Rossi']);
        $this->assertDatabaseHas('tl_users',['name' => 'Mario','surname' => 'Rossi']);

        // Need to check if the fields are really required
        $response = $this->post('/api/users',['name' => 'Mario',]);
        $response->assertStatus(422)->assertJson(['errors' => ['surname' => []]]);

        $response = $this->post('/api/users',['surname' => 'Rossi',]);
        $response->assertStatus(422)->assertJson(['errors' => ['name' => []]]);

        $response = $this->post('/api/users',[]);
        $response->assertStatus(422)->assertJson(['errors' => ['name' => [],'surname' => []]]);
    }

}
