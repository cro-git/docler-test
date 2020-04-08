<?php


namespace App\Domain\Base;


use Assert\Assert;
use Webpatser\Uuid\Uuid;

abstract class UuidType
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        Assert::that($value)->string();
        Assert::that(self::isValid($value))->true('Invalid '.$this->getType().'Id');

        $this->value = $value;
    }

    /**
     * @param string $value
     * @return static
     */
    public static function fromString($value)
    {
        return new static($value);
    }

    /**
     * @param $value
     * @return static
     */
    public static function create($value)
    {
        return new static($value);
    }

    /**
     * @return static
     */
    public static function generate()
    {
        return new static((string)Uuid::generate());
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isValid($value)
    {
        return Uuid::validate($value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param self $id
     * @return bool
     */
    public function equals(self $id)
    {
        if ($this->value === $id->getValue())
            return true;
        else
            return false;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return (string)$this->value;
    }

    /**
     * @return string
     */
    abstract public function getType();
}
