<?php


namespace App\Domain\User;

use Assert\Assertion;

class UserName
{
    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /**
     * UserName constructor.
     * @param $name
     * @param $surname
     */
    public function __construct($name, $surname)
    {
        Assertion::string($name);
        Assertion::string($surname);
        $this->name = $name;
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name.' '.$this->surname;
    }

    /**
     * @param self $name
     * @return bool
     */
    public function equals(UserName $name)
    {
        return ($this->name === $name->getFullName()
            && $this->surname === $name->getSurname());
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return (string)$this->name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return (string)$this->surname;
    }
}
