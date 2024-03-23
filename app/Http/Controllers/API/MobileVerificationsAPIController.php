<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMobileVerificationsAPIRequest;
use App\Http\Requests\API\UpdateMobileVerificationsAPIRequest;
use App\Models\MobileVerifications;
use App\Repositories\MobileVerificationsRepository;
use App\Services\CodeProcessor;
use App\Services\UsersService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class MobileVerificationsAPIController
 */
class MobileVerificationsAPIController extends AppBaseController
{
    private MobileVerificationsRepository $mobileVerificationsRepository;

    /** @var  UsersService */
    private $usersService;

    public function __construct(MobileVerificationsRepository $mobileVerificationsRepo, UsersService $usersService)
    {
        $this->mobileVerificationsRepository = $mobileVerificationsRepo;
        $this->usersService = $usersService;
    }

    /**
     * Display a listing of the MobileVerifications.
     * Display a listing of the MobileVerifications.
     * GET|HEAD /mobile-verifications
     */
    public function index(Request $request): JsonResponse
    {
        $mobileVerifications = $this->mobileVerificationsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($mobileVerifications->toArray(), 'Mobile Verifications retrieved successfully');
    }

    /**
     * Store a newly created MobileVerifications in storage.
     * POST /mobile-verifications
     */
    public function store(CreateMobileVerificationsAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $mobileVerifications = $this->mobileVerificationsRepository->create($input);

        return $this->sendResponse($mobileVerifications->toArray(), 'Mobile Verifications saved successfully');
    }

    /**
     * Display the specified MobileVerifications.
     * GET|HEAD /mobile-verifications/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var MobileVerifications $mobileVerifications */
        $mobileVerifications = $this->mobileVerificationsRepository->find($id);

        if (empty($mobileVerifications)) {
            return $this->sendError('Mobile Verifications not found');
        }

        return $this->sendResponse($mobileVerifications->toArray(), 'Mobile Verifications retrieved successfully');
    }

    /**
     * Update the specified MobileVerifications in storage.
     * PUT/PATCH /mobile-verifications/{id}
     */
    public function update($id, UpdateMobileVerificationsAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var MobileVerifications $mobileVerifications */
        $mobileVerifications = $this->mobileVerificationsRepository->find($id);

        if (empty($mobileVerifications)) {
            return $this->sendError('Mobile Verifications not found');
        }

        $mobileVerifications = $this->mobileVerificationsRepository->update($input, $id);

        return $this->sendResponse($mobileVerifications->toArray(), 'MobileVerifications updated successfully');
    }

    /**
     * Remove the specified MobileVerifications from storage.
     * DELETE /mobile-verifications/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var MobileVerifications $mobileVerifications */
        $mobileVerifications = $this->mobileVerificationsRepository->find($id);

        if (empty($mobileVerifications)) {
            return $this->sendError('Mobile Verifications not found');
        }

        $mobileVerifications->delete();

        return $this->sendSuccess('Mobile Verifications deleted successfully');
    }

    public function sendVerificationCode(CreateMobileVerificationsAPIRequest $request)
    {
        try {
            //check if mobile number has a valid verification code.
            $previousMobileVerifications = $this->mobileVerificationsRepository->findByMobileNumber($request->calling_code, $request->phone);

            if (count($previousMobileVerifications) > 0) {
              $rr =  $this->mobileVerificationsRepository->model()::where('phone' , $request->phone)->update(array('expired' => '1'));
            }
            // call code processor to generate verification code.
            $code = CodeProcessor::getInstance()->generateCode();
            $this->mobileVerificationsRepository->create(
                array(
                    'is_user' => 1,
                    'phone' => $request->phone,
                    'code' => $code ,
                    'expired' => 0 ,
                    'expired_at' => Carbon::now()->addMinutes(env('SMS_VERIFICATIONS_CODE_EXPIRE_IN' , 60))
                )
            );

            return $this->sendApiResponse(array('data' => $code) , 'Mobile verification code sent successfully.');

        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function validateVerificationCodeForVendor()
    {
        try {
            $mobileVerifications = $this->mobileVerificationsRepository->getByCode(\request('code'));
            if ($mobileVerifications) {
                $this->mobileVerificationsRepository->model()::Where('calling_code', '=', $mobileVerifications->calling_code)->where('phone' , $mobileVerifications->phone)->update(array('expired' => '1'));

                $user = $this->usersService->getById($mobileVerifications->user_id);
                if ($user) {
                    $user->update([
                        'calling_code' => $mobileVerifications->calling_code,
                        'mobile' => $mobileVerifications->phone,
                        'full_mobile_number' => $mobileVerifications->calling_code . $mobileVerifications->phone,
                    ]);

                    return $this->sendApiResponse(array(), 'User authenticated successfully.');
                }

                return $this->sendApiError(__('messages.Mobile_Not_Valid'), 500);
            }

            return $this->sendApiError(__('messages.Code_Not_Valid'), 500);
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function validateVerificationCode()
    {
        try {
            $mobileVerifications = $this->mobileVerificationsRepository->validateByCode(\request('code'));
            if ($mobileVerifications) {
                $this->mobileVerificationsRepository->model()::Where('is_verification', '=', 1)->where('phone' , $mobileVerifications->phone)->update(array('expired' => '1'));
                $data = ['is_user' =>$mobileVerifications->is_user , 'Verification' => true];
                  return $this->sendApiResponse(array('data' =>$data), 'Mobile Verification successfully.');

            }
            return $this->sendApiError(__('messages.Code_Not_Valid'), 500);
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
}
