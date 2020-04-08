<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserDetail;
use App\Repositories\CountryRepository;
use App\Repositories\UserRepository;
use App\User;
use Cacheable;

class UsersController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CountryRepository
     */
    private $countryRepository;


    public function __construct(UserRepository $userRepository,CountryRepository $countryRepository)
    {
        $this->userRepository = Cacheable::wrap($userRepository);
        $this->countryRepository = Cacheable::wrap($countryRepository);
    }

    public function index()
    {
        return $this->userRepository->active();
    }

    public function listByCitizenship($countryIso2)
    {
        $country = $this->countryRepository->findByIso2($countryIso2);
        if (!$country)
            return response()->json(['message' =>__('Country not found')], 404);

        return $this->userRepository->citizendOf($country->id);
    }

    public function updateUserWithDetail(User $user,UpdateUserDetail $request)
    {
        $data = $request->validated();
        if (!$user->detail)
            return response()->json(['message' => __('Only users with detail can be updated')],403);

        // We're expecting an iso2 code for the country, but the model needs and id, so we fetch the record and get the corresponding id
        // The country exists, it has been checked in the UpdateUserDetail request, so no need to check twice
        if (isset($data['citizenship']))
            $data['citizenship_country_id'] = $this->countryRepository->findByIso2($data['citizenship'])->id;

        $user->update($data);
        $user->detail->update($data);

        return response()->json(['message' => __('User updated')],200);
    }

    public function deleteUserWithoutDetail(User $user)
    {
        if ($user->detail)
            return response()->json(['message' => __('You can delete only users without detail')], 403);
        $user->delete();
        return response()->json(['message' => __('User deleted')],200);
    }
}
