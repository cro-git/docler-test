<?php


namespace App\Domain\Task;


use Assert\Assert;

class TaskStatus
{
    const DONE = 1;
    const TODO = 0;

    const AVAILABLE_STATUS = [
        self::DONE,
        self::TODO
    ];

    /** @var integer */
    private $status;

    /**
     * TaskStatus constructor.
     * @param int $status
     */
    public function __construct(int $status)
    {
        Assert::that($status)->integer();
        Assert::that(in_array($status,self::AVAILABLE_STATUS))->true('Invalid status');
        $this->status = $status;
    }

    public static function create(int $status)
    {
        return new TaskStatus($status);
    }

    public function equals(TaskStatus $status)
    {
        return $this->status === $status->getValue();
    }

    public function getValue()
    {
        return $this->status;
    }

    public function isDone()
    {
        return $this->status === self::DONE;
    }

}
