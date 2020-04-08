<?php


namespace Tests\Domain;

use App\Domain\Base\DateType;
use App\Domain\Base\StringType;
use DateTime;
use Tests\Domain\AbastractTestClasses\AbstractUuid;
use Tests\TestCase;
use Webpatser\Uuid\Uuid;

class BaseTest extends TestCase
{
    /**
     * Testing the base class UuidType
     */
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

    /**
     * Testing the base class StringType
     */
    public function testString()
    {
        $d1 = new StringType('test');
        $d2 = new StringType('test');
        $d3 = new StringType('test2');

        // Check the equals operator
        $this->assertTrue($d1->equals($d2));
        $this->assertFalse($d1->equals($d3));

        // Check the getValue to be sure it set and get the right value
        $this->assertEquals('test',$d1->getValue());
        $this->assertEquals('test',$d2->getValue());
        $this->assertEquals('test2',$d3->getValue());

        // Check the __toString method
        $this->assertEquals('test',(string)$d1);
    }

    /**
     * Testing the base class DateType
     */
    public function testDate()
    {
        $d1 = new DateType(new DateTime());
        $d2 = DateType::createFromString(date('d-m-Y'),'d-m-Y');
        $d3 = DateType::createFromString(date('d-m-Y',mktime(0,0,0,10,10,2000)),'d-m-Y');
        $yesterday = (new DateTime())->sub(new \DateInterval('P1D'));
        $tomorrow = (new DateTime())->add(new \DateInterval('P1D'));
        $dY = new DateType($yesterday);
        $dT = new DateType($tomorrow);

        // Check the equals operator
        $this->assertTrue($d1->equals($d2));
        $this->assertTrue($d2->equals($d1));
        $this->assertFalse($d1->equals($d3));
        $this->assertFalse($d1->equals($dY));

        // Check  today,tomorrow,yesterday operator
        $this->assertTrue($d1->isToday());
        $this->assertFalse($d1->isTomorrow());
        $this->assertFalse($d1->isYesterday());
        $this->assertFalse($d3->isToday());
        $this->assertTrue($dY->isYesterday());
        $this->assertTrue($dT->isTomorrow());

        // Check the __toString method
        $this->assertEquals(date('d-m-Y'),(string)$d1);
    }
}
