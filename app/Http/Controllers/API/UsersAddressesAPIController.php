<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUsersAddressesAPIRequest;
use App\Http\Requests\API\UpdateUsersAddressesAPIRequest;
use App\Http\Resources\UserAddressesResource;
use App\Models\UsersAddresses;
use App\Repositories\UsersAddressesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class UsersAddressesAPIController
 */
class UsersAddressesAPIController extends AppBaseController
{
    private UsersAddressesRepository $usersAddressesRepository;

    public function __construct(UsersAddressesRepository $usersAddressesRepo)
    {
        $this->usersAddressesRepository = $usersAddressesRepo;
    }

    /**
     * Display a listing of the UsersAddresses.
     * GET|HEAD /users-addresses
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->limit ? $request->limit : 20;
            $usersAddresses = $this->usersAddressesRepository->getByUser($this->getUser()->id, $limit);

            return  $this->sendApiResponse(array('data' => UserAddressesResource::collection($usersAddresses)), 'Users Addresses retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Store a newly created UsersAddresses in storage.
     * POST /users-addresses
     */
    public function store(CreateUsersAddressesAPIRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $input['user_id'] = $this->getUser()->id;
            $usersAddresses = $this->usersAddressesRepository->create($input);

            return  $this->sendApiResponse(array('data' => new UserAddressesResource($usersAddresses)), 'Users Addresses saved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Display the specified UsersAddresses.
     * GET|HEAD /users-addresses/{id}
     */
    public function show($id): JsonResponse
    {
        try {
            /** @var UsersAddresses $usersAddresses */
            $usersAddresses = $this->usersAddressesRepository->getByID($this->getUser()->id, $id);

            if (empty($usersAddresses)) {
                return $this->sendApiError('Users Addresses not found', 404);
            }

            return  $this->sendApiResponse(array('data' => new UserAddressesResource($usersAddresses)), 'Users Addresses retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Update the specified UsersAddresses in storage.
     * PUT/PATCH /users-addresses/{id}
     */
    public function update($id, UpdateUsersAddressesAPIRequest $request): JsonResponse
    {
        try {
            $input = $request->all();

            /** @var UsersAddresses $usersAddresses */
            $usersAddresses = $this->usersAddressesRepository->getByID($this->getUser()->id, $id);

            if (empty($usersAddresses)) {
                return $this->sendApiError('Users Addresses not found', 404);
            }

            $usersAddresses = $this->usersAddressesRepository->update($input, $id);

            return  $this->sendApiResponse(array('data' => new UserAddressesResource($usersAddresses)), 'Users Addresses retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Remove the specified UsersAddresses from storage.
     * DELETE /users-addresses/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        try {
            /** @var UsersAddresses $usersAddresses */
            $usersAddresses = $this->usersAddressesRepository->getByID($this->getUser()->id, $id);

            if (empty($usersAddresses)) {
                return $this->sendApiError('Users Addresses not found', 404);
            }

            $usersAddresses->delete();

            return  $this->sendApiResponse(array(), 'Users Addresses retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
}
