<?php


namespace App\Http\Controllers\Api;

use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Repository\TaskListRepositoryInterface;
use App\Domain\TaskList\Repository\TaskRepositoryInterface;
use App\Domain\TaskList\Serializer\TaskListSerializer;
use App\Domain\TaskList\Serializer\TaskSerializer;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeNameToTaskListRequest;
use App\Http\Requests\CreateTaskListRequest;
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
        return response()->json($this->serializer->jsonList($taskList->getTasks()));
    }

    public function createTask(CreateTaskListRequest $request)
    {
        $data = $request->validated();
        try {
            $userId = UserId::fromString($data['user_id']);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid UserID'],404);
        }

        $name = new TaskListName($data['name']);
        $taskList = TaskList::create($userId,$name);
        return response()->json($this->serializer->json($taskList));
    }

    public function changeNameToTaskList($id,ChangeNameToTaskListRequest $request)
    {
        try {
            $taskListId = TaskListId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid TaskListID'],404);
        }

        /** @var TaskList $taskList */
        $taskList = $this->taskListRepository->getTaskList($taskListId);

        $data = $request->validated();
        $name = new TaskListName($data['name']);
        $taskList = $taskList->changeName($name);

        return response()->json($this->serializer->json($taskList));
    }

    public function deleteTaskList($id)
    {
        try {
            $taskListId = TaskListId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid TaskListID'],404);
        }

        /** @var TaskList $taskList */
        $taskList = $this->taskListRepository->getTaskList($taskListId);

        $taskList->delete();

        return response()->json($this->serializer->json($taskList));
    }
}
