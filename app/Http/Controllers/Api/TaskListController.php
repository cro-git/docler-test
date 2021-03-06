<?php


namespace App\Http\Controllers\Api;

use App\Domain\TaskList\Models\TaskList\TaskList;
use App\Domain\TaskList\Models\TaskList\TaskListId;
use App\Domain\TaskList\Models\TaskList\TaskListName;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Repository\TaskListRepositoryInterface;
use App\Domain\TaskList\Repository\UsersRepositoryInterface;
use App\Domain\TaskList\Serializer\TaskListSerializer;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeNameToTaskListRequest;
use App\Http\Requests\CreateTaskListRequest;
use Exception;

class TaskListController extends Controller
{
    /**
     * @var UsersRepositoryInterface
     */
    private $userRepository;
    /**
     * @var TaskListRepositoryInterface
     */
    private $taskListRepository;
    /**
     * @var TaskListSerializer
     */
    private $serializer;

    public function __construct(TaskListRepositoryInterface $taskListRepository,UsersRepositoryInterface $userRepository,TaskListSerializer $serializer)
    {
        $this->taskListRepository = $taskListRepository;
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    public function listTaskLists($user_id)
    {
        try {
            $userId = UserId::fromString($user_id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid UserID'],404);
        }

        // This will check if the user exists and throw a 404 error if it's missing
        $this->userRepository->getUser($userId);

        $taskLists = $this->taskListRepository->getTaskListsByUser($userId);
        return response()->json($this->serializer->jsonList($taskLists));
    }

    public function createTaskList(CreateTaskListRequest $request)
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

    public function getTaskListDetail($id)
    {
        try {
            $taskListId = TaskListId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid TaskListID'],404);
        }

        /** @var TaskList $taskList */
        $taskList = $this->taskListRepository->getTaskList($taskListId);

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
