<?php


namespace Tests\Domain;


use App\Country;
use App\Domain\User;
use App\Domain\User\UserName;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Testing the domain class UserName
     */
    public function testUserName()
    {
        $n1 = new UserName('Mario','Rossi');
        $n2 = new UserName('Mario','Draghi');
        $n3 = new UserName('Paolo','Rossi');
        $n4 = new UserName('Mario','Rossi');

        // Check the equals operator
        $this->assertFalse($n1->equals($n2));
        $this->assertFalse($n1->equals($n3));
        $this->assertFalse($n2->equals($n3));
        $this->assertTrue($n1->equals($n4));

        // Check the get name / surname
        $this->assertEquals('Mario',$n1->getName());
        $this->assertEquals('Paolo',$n3->getName());
        $this->assertEquals('Rossi',$n1->getSurname());
        $this->assertEquals('Draghi',$n2->getSurname());

        // Check the full name
        $this->assertEquals('Mario Rossi',$n1->getFullName());
        $this->assertEquals('Mario Draghi',$n2->getFullName());

        // Check the to-string conversion
        $this->assertEquals('Mario Rossi',(string)$n1);
        $this->assertEquals('Mario Draghi',(string)$n2);
    }

    /**
     * Testing the domain class User
     */
    public function testUser()
    {
        $u1 = User::create(new UserName('Mario','Rossi'));
        $u2 = User::create(new UserName('Mario','Rossi'));
        $u3 = new User($u1->getId(),$u1->getName());
        $u4 = new User($u1->getId(),new UserName('Paolo','Draghi'));

        // Check if the name is assigned correctly to the user
        $this->assertEquals('Mario Rossi',$u1->getName()->getFullName());

        // Check if equals works as expected
        $this->assertFalse($u1->equals($u2));
        $this->assertTrue($u1->equals($u3));
        $this->assertTrue($u1->equals($u4));

        // Check if the name change correctly when we change name
        $u1->changeName(new UserName('Giovanni','Belvedere'));
        $this->assertEquals('Giovanni Belvedere',$u1->getName()->getFullName());

        // Check if the comparison works again even when we change name
        $this->assertTrue($u1->equals($u3));
    }
}
