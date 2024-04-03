<?php

namespace App\Services;


use App\Models\DoctorsSchedules;
use App\Models\User;
use App\Repositories\MobileVerificationsRepository;
use App\Repositories\UserRepository;
use App\Repositories\UsersServicesRepository;
use Carbon\Carbon;

class UsersService
{
    /**
     * @var $userRepository
     */
    protected $userRepository;


    /** @var  MobileVerificationsRepository */
    private $mobileVerificationsRepository;


    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     * @param MobileVerificationsRepository $mobileVerificationsRepo
     */
    public function __construct(UserRepository $userRepository, MobileVerificationsRepository $mobileVerificationsRepo)
    {
        $this->userRepository = $userRepository;
        $this->mobileVerificationsRepository = $mobileVerificationsRepo;
    }


    /**
     * Get user by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->userRepository->model()::with(['roles', 'roles.permissions'])->find($id);
    }

    public function findUserByMobileNumber($mobileNumber)
    {
        return $this->userRepository->model()::Where('phone', '=', $mobileNumber)->first();
    }

    public function getAll()
    {
        return $this->userRepository->model()::with('roles')->get();
    }

    /**
     * Get user by id.
     *
     * @param $id
     * @return String
     */
    public function getInfo($id)
    {
        return $this->userRepository->model()::with(['roles'])->find($id);
    }


    public function getNotifications($user, $limit)
    {
        return $user->notifications()->paginate($limit);
    }

    /**
     * Get user by id.
     *
     * @param $mobile
     * @return String
     */
    public function getByMobile($mobile)
    {
        return $this->userRepository->model()::where('full_mobile_number', '=', $mobile)->first();
    }

    /**
     * Create new user.
     *
     * @param $input
     * @return String
     */
    public function create($input)
    {
        return $this->userRepository->create($input);
    }

    /**
     * Update user.
     *
     * @param $input
     * @param $id
     * @return String
     */
    public function update($input, $id)
    {
        return $this->userRepository->update($input, $id);
    }

    /**
     * @param $request
     * @param $full_mobile_number
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function register($full_mobile_number, $request)
    {
        $newUser = collect([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'gender' => $request->gender,
            'calling_code' => $request->calling_code,
            'phone' => $request->phone,
            'full_mobile_number' => $full_mobile_number,
            'date_of_birth' => $request->date_of_birth,
        ]);
        return $this->userRepository->create($newUser->toArray());
    }
}
