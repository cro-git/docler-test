<?php


namespace Tests\Domain;


use App\Country;
use App\Domain\Task;
use App\Domain\Task\TaskDescription;
use App\Domain\Task\TaskStatus;
use App\Domain\User;
use App\Domain\User\UserName;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * Testing the domain class UserName
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

}
