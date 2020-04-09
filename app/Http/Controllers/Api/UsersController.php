<?php


namespace App\Http\Controllers\Api;

use App\DbPersistence\Repository\UsersRepository;
use App\Domain\TaskList\Models\User\User;
use App\Domain\TaskList\Models\User\UserName;
use App\Domain\TaskList\Serializer\UserSerializer;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserReqeust;

class UsersController extends Controller
{

    /**
     * @var UsersRepository
     */
    private $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function listUsers()
    {
        $users = $this->usersRepository->getAllUsers();
        return response()->json(UserSerializer::jsonList($users));
    }

    public function createUser(CreateUserRequest $request)
    {
        $data = $request->validated();

        $name = new UserName($data['name'],$data['surname']);
        $user = User::create($name);
        return response()->json(UserSerializer::json($user));
    }

    public function getUserDetail($id)
    {
        return response()->json([]);
    }

    public function updateUser($id,UpdateUserReqeust $reqeust)
    {

    }

    public function deleteUser($id)
    {

    }
}
