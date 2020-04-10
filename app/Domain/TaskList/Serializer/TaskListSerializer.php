<?php
namespace  App\Domain\TaskList\Serializer;

use App\Domain\TaskList\Models\TaskList\TaskList;


class TaskListSerializer extends BaseSerializer
{
    /**
     * @var TaskSerializer
     */
    private $taskSerializer;

    /**
     * TaskListSerializer constructor.
     * @param TaskSerializer $taskSerializer
     */
    public function __construct(TaskSerializer $taskSerializer)
    {
        $this->taskSerializer = $taskSerializer;
    }

    public function json(TaskList $taskList)
    {
        return [
            'id' => (string)$taskList->getId(),
            'name' => (string)$taskList->getName(),
            'tasks' => $this->taskSerializer->jsonList($taskList->getTasks())
        ];
    }
}
