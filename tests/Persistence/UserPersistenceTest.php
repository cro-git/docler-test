<?php


use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserName;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserPersistenceTest extends TestCase
{
    use DatabaseTransactions;

    public function testPersistency()
    {
        // Fror the test to be effective we need to make sure we don't already have this records in the db
        // Yes, of course we could count on the DatabaseTransactions and a goog test build with special db etc, but better to be sure
        $this->assertDatabaseMissing('tl_users',['name' => 'Mario','surname' => 'Rossi']);
        $this->assertDatabaseMissing('tl_users',['name' => 'Gino','surname' => 'Trottolino']);

        // We create an user and we expect the db to have this user
        $u1 = User::create(new UserName('Mario','Rossi'));
        $this->assertDatabaseHas('tl_users', ['id'=> $u1->getId()->getValue(), 'name' => 'Mario', 'surname' => 'Rossi']);

        $u1->changeName(new UserName('Gino','Trottolino'));
        // The old name disappears, and the new name appear
        $this->assertDatabaseMissing('tl_users',['name' => 'Mario','surname' => 'Rossi']);
        $this->assertDatabaseHas('tl_users', ['id'=> $u1->getId()->getValue(), 'name' => 'Gino','surname' => 'Trottolino']);

        // Test delete
        $u1->delete();
        $this->assertDatabaseMissing('tl_users',['id'=> $u1->getId()->getValue()]);
    }

}
