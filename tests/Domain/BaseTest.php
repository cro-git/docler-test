<?php


namespace Tests\Domain;

use AbstractUuid;
use Tests\TestCase;
use Webpatser\Uuid\Uuid;

class BaseTest extends TestCase
{
    public function testBaseUid()
    {
        $u1 = AbstractUuid::generate();
        $u2 = AbstractUuid::generate();
        $u3 = AbstractUuid::create($u1->getValue());
        $u4 = new AbstractUuid($u1->getValue());
        $u5 = AbstractUuid::fromString($u1->getValue());

        // Check the equals operator
        $this->assertFalse($u1->equals($u2));
        $this->assertTrue($u1->equals($u3));
        $this->assertTrue($u1->equals($u4));
        $this->assertTrue($u1->equals($u5));

        // Check the 'isValid' operator
        $this->assertTrue(AbstractUuid::isValid($u1->getValue()));
        $this->assertFalse(AbstractUuid::isValid('test123'));

        // Check with the library Uuid to be sure we are validating correctly
        $this->assertTrue(Uuid::validate($u1->getValue()));
        $this->assertTrue(Uuid::validate($u2->getValue()));
    }
}
