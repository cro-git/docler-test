<?php
namespace  App\Domain\TaskList\Serializer;

use App\Domain\TaskList\Models\Task\Task;
use App\Domain\TaskList\Models\Task\TaskStatus;


class TaskSerializer extends BaseSerializer
{
    public function json(Task $task)
    {
        return [
            'id' => (string)$task->getId(),
            'description' => (string)$task->getDescription(),
            'status' => $this->getStatusName($task->getStatus()->getValue()),
            'due_date' => $task->getDueDate()->getValue()->format('d-m-Y')
        ];
    }

    private function getStatusName($status)
    {
        if ($status === TaskStatus::DONE)
            return 'Done';
        return 'Todo';
    }
}
