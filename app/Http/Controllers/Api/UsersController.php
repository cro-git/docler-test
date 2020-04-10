<?php


namespace App\Http\Controllers\Api;

use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserId;
use App\Domain\TaskList\Models\User\UserName;
use App\Domain\TaskList\Repository\UsersRepositoryInterface;
use App\Domain\TaskList\Serializer\UserSerializer;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserReqeust;
use Exception;

class UsersController extends Controller
{

    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;
    /**
     * @var UserSerializer
     */
    private $serializer;


    public function __construct(UsersRepositoryInterface $usersRepository,UserSerializer $serializer)
    {
        $this->usersRepository = $usersRepository;
        $this->serializer = $serializer;
    }

    public function listUsers()
    {
        $users = $this->usersRepository->getAllUsers();
        return response()->json($this->serializer->jsonList($users));
    }

    public function createUser(CreateUserRequest $request)
    {
        $data = $request->validated();

        $name = new UserName($data['name'],$data['surname']);
        $user = User::create($name);
        return response()->json($this->serializer->json($user));
    }

    public function getUserDetail($id)
    {
        try {
            $userId = UserId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid UserID'],404);
        }

        $user = $this->usersRepository->getUser($userId);
        return response()->json($this->serializer->json($user));
    }

    public function updateUser($id,UpdateUserReqeust $request)
    {
        try {
            $userId = UserId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid UserID'],404);
        }

        $user = $this->usersRepository->getUser($userId);

        $data = $request->validated();
        $name = new UserName($data['name'],$data['surname']);
        $user->changeName($name);

        return response()->json($this->serializer->json($user));
    }

    public function deleteUser($id)
    {
        try {
            $userId = UserId::fromString($id);
        }
        catch (Exception $exception) {
            return response()->json(['error' => 'Not a valid UserID'],404);
        }
        $user = $this->usersRepository->getUser($userId);

        $user->delete();

        return response()->json($this->serializer->json($user));
    }
}
