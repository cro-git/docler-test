<?php


namespace App\Domain\Base;


use DateTime;

class DateType
{
    /** @var DateTime */
    protected $date;

    /**
     * DateType constructor.
     * @param DateTime $date
     */
    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->date->format('d-m-Y');
    }

    /**
     * @param self $date
     * @return bool
     */
    public function equals(self $date)
    {
        return $this->date->getTimestamp() === $date->getValue()->getTimestamp();
    }

    /**
     * @param string $string the date in a string format ex: '2010-01-01'
     * @param string $format as defined in DateTime $format ex: 'YYYY-MM-DD'
     * @return static
     */
    public static function createFromString($string,$format)
    {
        return new static(DateTime::createFromFormat($format,$string));
    }

    /**
     * @return DateTime
     */
    public function getValue()
    {
        return $this->date;
    }

    /**
     * @return bool
     */
    public function isToday()
    {
        $date = new DateTime();
        $interval = $date->diff($this->date);
        return $interval->days == 0;
    }

    /**
     * @return bool
     */
    public function isYesterday()
    {
        $date = new DateTime();
        $interval = $date->diff($this->date);
        return $interval->days == 1 && $interval->invert == 1;
    }

    /**
     * @return bool
     */
    public function isTomorrow()
    {
        $date = new DateTime();
        $interval = $date->diff($this->date);
        return $interval->days == 1 && $interval->invert == 0;
    }
}
