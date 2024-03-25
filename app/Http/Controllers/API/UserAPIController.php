<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\LoginAPIRequest;
use App\Http\Requests\API\UpdatePasswordAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Repositories\UserRepository;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserAPIController
 */
class UserAPIController extends AppBaseController
{
    private UserRepository $userRepository;

    /** @var  UsersService */
    private $usersService;

    public function __construct(UserRepository $userRepo, UsersService $usersService)
    {
        $this->userRepository = $userRepo;
        $this->usersService = $usersService;
    }

    /**
     * Display a listing of the Users.
     * GET|HEAD /users
     */
    public function index(Request $request): JsonResponse
    {
        $users = $this->userRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
    }

    /**
     * Store a newly created User in storage.
     * POST /users
     */
    public function store(CreateUserAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        return $this->sendResponse($user->toArray(), 'User saved successfully');
    }

    /**
     * Display the specified User.
     * GET|HEAD /users/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse($user->toArray(), 'User retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /users/{id}
     */
    public function update($id, UpdateUserAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse($user->toArray(), 'User updated successfully');
    }

    /**
     * Remove the specified User from storage.
     * DELETE /users/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user->delete();

        return $this->sendSuccess('User deleted successfully');
    }

    public function getUserSplash()
    {
        try {
            $response = array(
                'points' => 10
            );

            return $this->sendApiResponse(array('data' => $response), __('messages.retrieved_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function register(CreateUserAPIRequest $request)
    {
        try {
            $user = $this->usersService->register($request);
            if (!$user) {
                return $this->sendApiError(__('messages.something_went_wrong'), 404);
            }

            if ($request->hasFile('avatar')) {
                $user->addMediaFromRequest('avatar')->toMediaCollection('user_avatar');
            }

            $userRole = \DB::table('roles')->where('name', '=', 'user')->pluck('id');
            $user->roles()->attach($userRole);

            $user = $this->usersService->getById($user->id);
            $token = $user->createToken('API Token')->accessToken;
            $response = array('user' => $user, 'token' => $token);
            return $this->sendApiResponse(array('data' => $response), __('auth.register_success'));

        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function login(LoginAPIRequest $request)
    {
        try {
            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (!auth()->attempt($data)) {
                return $this->sendApiError(__('auth.failed'), 403);
            }
            $token = auth()->user()->createToken('API Token');

            $user = $this->usersService->getById(auth()->id());

            if (!$user) {
                return $this->sendApiError(__('passwords.user'), 404);
            }

            $data = ['user' => $user, 'token' => $token->accessToken];
            return $this->sendApiResponse(array('data' => $data), trans('auth.login_success'));

        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function profile()
    {
        try {
            $user = $this->usersService->getInfo($this->getUser()->id);
            $response = array(
                'data' => $user,
            );

            return $this->sendApiResponse($response, __('messages.retrieved_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $input = $request->all();
            /** @var User $user */
            $user = $this->usersService->getById($this->getUser()->id);

            if (empty($user)) {
                return $this->sendApiError(__('passwords.user'), 404);
            }

            $rules = \App\Models\User::$edit_rules;
            $rules['email'] = $rules['email'] . ',id,' . $user->id;
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $errorString = implode(", ",$validator->messages()->all());
                return $this->sendApiError($errorString, 422);
            }

            $user = $this->usersService->update($input, $this->getUser()->id);

            if ($request->hasFile('avatar')) {
                $user->clearMediaCollection('user_avatar');
                $user->addMediaFromRequest('avatar')->toMediaCollection('user_avatar');
            }

            $updated_user = $this->usersService->getById($user->id);
            return $this->sendApiResponse(array('data' => $updated_user), __('messages.update_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function changePassword(UpdatePasswordAPIRequest $request)
    {
        try {
            $user = $this->usersService->getById($this->getUser()->id);
            if (!$user) {
                return $this->sendApiError(__('passwords.user'), 404);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->sendApiError(__('passwords.incorrect_password'), 422);
            }

            $this->usersService->update(["password" => bcrypt($request->password)],$user->id);
            return $this->sendApiResponse(array(), __('messages.update_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function interestedCategories(Request $request)
    {
        try {
            $user = $this->usersService->getById($this->getUser()->id);
            if (!$user) {
                return $this->sendApiError(__('passwords.user'), 404);
            }

            $user->interestCategories()->sync($request->categories);
            return $this->sendApiResponse(array('data' => new UserResource($user)), __('messages.update_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return $this->sendApiResponse(array(), __('auth.logout_success'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
}
