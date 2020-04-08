<?php


namespace App\Domain;


use App\Domain\Task\TaskDescription;
use App\Domain\Task\TaskDueDate;
use App\Domain\Task\TaskId;
use App\Domain\Task\TaskStatus;

class Task
{
    /** @var TaskId */
    private $id;

    /** @var TaskDescription */
    private $description;

    /** @var TaskStatus */
    private $status;
    /**
     * @var TaskDueDate
     */
    private $date;

    /**
     * Task constructor.
     * @param TaskId $id
     * @param TaskDescription $description
     * @param TaskStatus $status
     * @param TaskDueDate $date
     */
    public function __construct(TaskId $id, TaskDescription $description,TaskStatus $status,TaskDueDate $date)
    {
        $this->id = $id;
        $this->description = $description;
        $this->status = $status;
        $this->date = $date;
    }

    /**
     * @param TaskDescription $description
     * @param TaskDueDate $date
     * @return Task
     */
    public static function create(TaskDescription $description,TaskDueDate $date)
    {
        return new Task(
            TaskId::generate(),
            $description,
            TaskStatus::create(TaskStatus::TODO),
            $date
        );
    }

    /**
     * @return $this
     */
    public function setAsDone()
    {
        $this->status = TaskStatus::create(TaskStatus::DONE);
        return $this;
    }

    /**
     * @return $this
     */
    public function setAsTodo()
    {
        $this->status = TaskStatus::create(TaskStatus::TODO);
        return $this;
    }

    /**
     * @param TaskDescription $description
     * @return $this
     */
    public function setDescription(TaskDescription $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param TaskDueDate $date
     * @return $this
     */
    public function changeDate(TaskDueDate $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDueToday()
    {
        return $this->date->isToday();
    }

    /**
     * @return bool
     */
    public function isDone()
    {
        return $this->status->isDone();
    }

    /**
     * @return TaskStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return TaskDescription
     */
    public function getDescription()
    {
        return $this->description;
    }
}
