<?php


namespace App\Domain\TaskList\Models\Task;

use App\Domain\TaskList\Event\Task\TaskHasBeenCreated;
use App\Domain\TaskList\Event\Task\TaskHasBeenUpdated;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use DateTime;

class Task
{
    /** @var TaskId */
    private $id;

    /** @var TaskListId */
    private $taskListId;

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
     * @param TaskListId $taskListId
     */
    public function __construct(TaskId $id, TaskDescription $description,TaskStatus $status,TaskDueDate $date,TaskListId $taskListId)
    {
        $this->id = $id;
        $this->description = $description;
        $this->status = $status;
        $this->date = $date;
        $this->taskListId = $taskListId;
    }

    /**
     * @param TaskDescription $description
     * @param TaskListId $taskListId
     * @param TaskDueDate $date
     * @param TaskStatus $status
     * @return Task
     */
    public static function create(TaskDescription $description,TaskListId $taskListId,$date = null,$status = null)
    {
        if (!$status) $status = TaskStatus::create(TaskStatus::TODO);
        if (!$date) $date = new TaskDueDate(new DateTime());

        $task = new Task(TaskId::generate(), $description, $status, $date, $taskListId);
        event(new TaskHasBeenCreated($task));
        return  $task;
    }

    /**
     * @return $this
     */
    public function setAsDone()
    {
        $this->status = TaskStatus::create(TaskStatus::DONE);
        event(new TaskHasBeenUpdated($this));
        return $this;
    }

    /**
     * @return $this
     */
    public function setAsTodo()
    {
        $this->status = TaskStatus::create(TaskStatus::TODO);
        event(new TaskHasBeenUpdated($this));
        return $this;
    }

    /**
     * @param TaskDescription $description
     * @return $this
     */
    public function setDescription(TaskDescription $description)
    {
        $this->description = $description;
        event(new TaskHasBeenUpdated($this));
        return $this;
    }

    /**
     * @param TaskDueDate $date
     * @return $this
     */
    public function changeDate(TaskDueDate $date)
    {
        $this->date = $date;
        event(new TaskHasBeenUpdated($this));
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
     * @return TaskId
     */
    public function getId()
    {
        return $this->id;
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

    public function equals(Task $task)
    {
        return $this->getId()->equals($task->getId());
    }
}
