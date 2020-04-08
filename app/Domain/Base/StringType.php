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
        Assert::that($value)->string();
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
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
