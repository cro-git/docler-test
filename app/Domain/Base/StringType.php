<?php


namespace App\Domain\Base;


use Assert\Assert;

class StringType
{
    /** @var string */
    protected $value;

    /**
     * StringType constructor.
     * @param $value
     */
    public function __construct($value)
    {
        Assert::string($value);
        $this->value = $value;
    }

    public function equals(self $string)
    {
        return $this->value === $string->getValue();
    }

    public function getValue()
    {
        return $this->value;
    }
}
