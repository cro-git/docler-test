<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Note: this test suppose you just seeded the database
     * use: php artisan db:seed --class=DatabaseSeede
     * The test use transactions so at the end of the test the database will not be changed
     */

    /**
     * 1. Create a call which will return all the users which are active (users table) and have an Austrian citizenship.
     *
     * @return void
     */
    public function testUsersByCityzenship()
    {
        // Only the user 1 and 6 match the description ( active, with a detail, and the right citizenship )
        $response = $this->get('/api/users/citizenship/AT');
        $response->assertStatus(200)
            ->assertJson([
                ['id' => 1],
                ['id' => 6],
            ]);

        $response = $this->get('/api/users/citizenship/ES');
        $response->assertStatus(200)
            ->assertJson([]);

        $response = $this->get('/api/users/citizenship/UK');
        $response->assertStatus(404)
            ->assertJson(['message' => 'Country not found']);
    }

    /**
     * Create a call which will allow you to edit user details just if the user details are there already.
     *
     * @return void
     */
    public function testUpdateUserWithDetail()
    {
        // Check the country validator
        $response = $this->post('/api/users/1/detail',['citizenship' => 'WRONG_ISO']);
        $response->assertStatus(422)
            ->assertJson(['citizenship' => ['Invalid country for citizenship']]);

        // Check the phone validator
        $response = $this->post('/api/users/2/detail',['phone_number' => 'HELLO']);
        $response->assertStatus(422)
            ->assertJson(['phone_number' => ['phone_number is an invalid phone number']]);

        // Only users with detail
        $response = $this->post('/api/users/2/detail',['citizenship' => 'DE']);
        $response->assertStatus(403)
            ->assertJson(['message' => 'Only users with detail can be updated']);

        // We check an invald user
        $response = $this->post('/api/users/200/detail',['citizenship' => 'DE']);
        $response->assertStatus(404);


        // We need to check if all the fields will be updated with the call
        $response = $this->post('/api/users/1/detail',['citizenship' => 'DE','first_name' => 'FirstName','last_name' => 'LastName','phone_number' => '+39 9999999','active' => false,'email' => 'test@email.com']);
        $response->assertStatus(200);

        $this->assertDatabaseHas('user_details', [
            'user_id' => 1,
            'first_name' => 'FirstName',
            'last_name' => 'LastName',
            'phone_number' => '+39 9999999',
            'citizenship_country_id' => 3
        ]);
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'active' => 0,
            'email' => 'test@email.com'
        ]);
    }


    /**
     *  Create a call which will allow you to delete a user just if no user details exist yet.
     */
    public function testDeleteUserWithoutDetail()
    {
        // We are not allowed to edit users with detail ( user 1 has detail )
        $response = $this->delete('/api/users/1');
        $response->assertStatus(403)
            ->assertJson(['message' => 'You can delete only users without detail']);

        // We will delete the user 2 ( no detail here )
        $response = $this->delete('/api/users/2');
        $response->assertStatus(200)
            ->assertJson(['message' => 'User deleted']);

        // Just to be sure, we deleted it right?
        $response = $this->delete('/api/users/2');
        $response->assertStatus(404);

        // If we try to delete a non-existing record it should return 404
        $response = $this->delete('/api/users/500');
        $response->assertStatus(404);
    }
}
