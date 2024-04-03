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
            $fullMobileNumber = $request->calling_code . $request->phone;
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $phoneNumber = $phoneUtil->parse($fullMobileNumber , null , null , true);
            $isValid = $phoneUtil->isValidNumber($phoneNumber);
            $phoneNumberFormated = $phoneUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164);
            if (!$isValid) {
                return $this->sendApiError(__('auth.phoneNotValid') , 500);
            }
            //check if mobile number has a valid verification code.
            $previousMobileVerifications = $this->mobileVerificationsRepository->findByMobileNumber($phoneNumberFormated);
            if (count($previousMobileVerifications) > 0) {
              $this->mobileVerificationsRepository->model()::where('phone' , $phoneNumberFormated)->update(array('expired' => '1'));
            }
            // call code processor to generate verification code.
            $code = CodeProcessor::getInstance()->generateCode();
            $this->mobileVerificationsRepository->create(
                array(
                    'is_user' => 1,
                    'phone' => $phoneNumberFormated,
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

    public function validateVerificationCode()
    {
        try {
            $mobileVerifications = $this->mobileVerificationsRepository->validateByCode(\request('code'));
            if ($mobileVerifications) {
                $mobileVerifications->update(array('expired' => 1, 'is_verification' => 1));
                $data = ['is_user' =>$mobileVerifications->is_user , 'is_verification' => true];
                  return $this->sendApiResponse(array('data' =>$data), 'Mobile Verification successfully.');

            }
            return $this->sendApiError(__('messages.Code_Not_Valid'), 422);
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
}
