<?php


namespace App\Http\Controllers\Api;

use App\Domain\TaskList\Models\Task\Task;
use App\Domain\TaskList\Models\Task\TaskDescription;
use App\Domain\TaskList\Models\Task\TaskDueDate;
use App\Domain\TaskList\Models\Task\TaskId;
use App\Domain\TaskList\Models\Task\TaskStatus;
use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Repository\TaskListRepositoryInterface;
use App\Domain\TaskList\Repository\TaskRepositoryInterface;
use App\Domain\TaskList\Serializer\TaskListSerializer;
use App\Domain\TaskList\Serializer\TaskSerializer;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Exception;

class TaskController extends Controller
{

    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;
    /**
     * @var TaskListSerializer
     */
    private $serializer;
    /**
     * @var TaskListRepositoryInterface
     */
    private $taskListRepository;

    public function __construct(TaskRepositoryInterface $taskRepository,TaskListRepositoryInterface $taskListRepository,TaskSerializer $serializer)
    {
        $this->taskRepository = $taskRepository;
        $this->taskListRepository = $taskListRepository;
        $this->serializer = $serializer;
    }

    public function listTask($task_list_id)
    {
        try {
            $taskListId = TaskListId::fromString($task_list_id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid TaskListID'],404);
        }

        /** @var TaskList $taskList */
        $taskList = $this->taskListRepository->getTaskList($taskListId);
        $tasks = $taskList->getTasks();

        return response()->json($this->serializer->jsonList($tasks));
    }

    public function createTask(CreateTaskRequest $request)
    {
        $data = $request->validated();
        try {
            $taskListId = TaskListId::fromString($data['task_list_id']);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid TaskListID'],404);
        }

        $description = new TaskDescription($data['description']);
        $due_date = null;
        if (isset($data['due_date']))
            $due_date = TaskDueDate::createFromString($data['due_date'],'d-m-Y');
        $status = null;
        if (isset($data['status'])) {
            try {
                $status = new TaskStatus($data['status']);
            }
            catch (Exception $exception) {
                return response()->json(['errors' => ['status' => ['Not a valid Status']]],422);
            }
        }

        $task = Task::create($description,$taskListId,$due_date,$status);
        return response()->json($this->serializer->json($task));
    }

    public function taskDetail($id)
    {
        try {
            $taskId = TaskId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid TaskID'],404);
        }

        /** @var Task $task */
        $task = $this->taskRepository->getTask($taskId);

        return response()->json($this->serializer->json($task));
    }

    public function updateTask($id,UpdateTaskRequest $request)
    {
        try {
            $taskId = TaskId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid TaskID'],404);
        }

        /** @var Task $task */
        $task = $this->taskRepository->getTask($taskId);

        $data = $request->validated();
        if (isset($data['description']))
            $task->setDescription(new TaskDescription($data['description']));

        if (isset($data['due_date'])) {
            $due_date = TaskDueDate::createFromString($data['due_date'], 'd-m-Y');
            $task->changeDate($due_date);
        }

        if (isset($data['status'])) {
            try {
                $status = new TaskStatus($data['status']);
            }
            catch (Exception $exception) {
                return response()->json(['errors' => ['status' => ['Not a valid Status']]],422);
            }
            if ($status->isDone())
                $task->setAsDone();
            else
                $task->setAsTodo();
        }

        return response()->json($this->serializer->json($task));
    }

    public function deleteTask($id)
    {
        try {
            $taskId = TaskId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid TaskID'],404);
        }

        /** @var Task $task */
        $task = $this->taskRepository->getTask($taskId);

        $task->delete();

        return response()->json($this->serializer->json($task));
    }
}
