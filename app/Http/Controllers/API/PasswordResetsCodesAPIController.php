<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePasswordResetsCodesAPIRequest;
use App\Http\Requests\API\ResetPasswordAPIRequest;
use App\Http\Requests\API\UpdatePasswordResetsCodesAPIRequest;
use App\Models\PasswordResetsCodes;
use App\Repositories\PasswordResetsCodesRepository;
use App\Services\CodeProcessor;
use App\Services\SMSService;
use App\Services\UsersService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class PasswordResetsCodesController
 * @package App\Http\Controllers\API
 */

class PasswordResetsCodesAPIController extends AppBaseController
{
    /** @var  PasswordResetsCodesRepository */
    private $passwordResetsCodesRepository;

    /** @var  UsersService */
    private $usersService;

    /** @var  SMSService */
    private $SMSService;

    public function __construct(PasswordResetsCodesRepository $passwordResetsCodesRepo, UsersService $usersService, SMSService $SMSService)
    {
        $this->passwordResetsCodesRepository = $passwordResetsCodesRepo;
        $this->usersService = $usersService;
        $this->SMSService = $SMSService;
    }

    /**
     * Display a listing of the PasswordResetsCodes.
     * GET|HEAD /passwordResetsCodes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $passwordResetsCodes = $this->passwordResetsCodesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($passwordResetsCodes->toArray(), 'Password Resets Codes retrieved successfully');
    }

    /**
     * Store a newly created PasswordResetsCodes in storage.
     * POST /passwordResetsCodes
     *
     * @param CreatePasswordResetsCodesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePasswordResetsCodesAPIRequest $request)
    {
        $input = $request->all();

        $passwordResetsCodes = $this->passwordResetsCodesRepository->create($input);

        return $this->sendResponse($passwordResetsCodes->toArray(), 'Password Resets Codes saved successfully');
    }

    /**
     * Display the specified PasswordResetsCodes.
     * GET|HEAD /passwordResetsCodes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PasswordResetsCodes $passwordResetsCodes */
        $passwordResetsCodes = $this->passwordResetsCodesRepository->find($id);

        if (empty($passwordResetsCodes)) {
            return $this->sendError('Password Resets Codes not found');
        }

        return $this->sendResponse($passwordResetsCodes->toArray(), 'Password Resets Codes retrieved successfully');
    }

    /**
     * Update the specified PasswordResetsCodes in storage.
     * PUT/PATCH /passwordResetsCodes/{id}
     *
     * @param int $id
     * @param UpdatePasswordResetsCodesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePasswordResetsCodesAPIRequest $request)
    {
        $input = $request->all();

        /** @var PasswordResetsCodes $passwordResetsCodes */
        $passwordResetsCodes = $this->passwordResetsCodesRepository->find($id);

        if (empty($passwordResetsCodes)) {
            return $this->sendError('Password Resets Codes not found');
        }

        $passwordResetsCodes = $this->passwordResetsCodesRepository->update($input, $id);

        return $this->sendResponse($passwordResetsCodes->toArray(), 'PasswordResetsCodes updated successfully');
    }

    /**
     * Remove the specified PasswordResetsCodes from storage.
     * DELETE /passwordResetsCodes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PasswordResetsCodes $passwordResetsCodes */
        $passwordResetsCodes = $this->passwordResetsCodesRepository->find($id);

        if (empty($passwordResetsCodes)) {
            return $this->sendError('Password Resets Codes not found');
        }

        $passwordResetsCodes->delete();

        return $this->sendSuccess('Password Resets Codes deleted successfully');
    }

    public function forgetPassword(CreatePasswordResetsCodesAPIRequest $request)
    {
        try {
            $user = $this->passwordResetsCodesRepository->findUserByFullMobileNumber($request->mobile);
            if ($user) {
                //check if mobile number has a valid verification code.
                $previousPasswordReset = $this->passwordResetsCodesRepository->findByMobileNumber($user->full_mobile_number);

                if (count($previousPasswordReset) > 0) {
                    $this->passwordResetsCodesRepository->makeModel()->where('mobile', $user->full_mobile_number)->update(array('expired' => '1'));
                }

                // call code processor to generate verification code.
                $code = CodeProcessor::getInstance()->generateCode();
                $this->passwordResetsCodesRepository->create(
                    array(
                        'mobile' => $user->full_mobile_number,
                        'code' => $code,
                        'email' => $user->email,
                        'expired' => 0,
                        'expired_at' => Carbon::now()->addMinutes(env('SMS_VERIFICATIONS_CODE_EXPIRE_IN', 15))
                    )
                );

                return $this->sendApiResponse(array('code' => $code), 'Password Rest code sent successfully.');
            } else {
                return $this->sendApiError(__('messages.user'), 500);
            }
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function resetPassword(ResetPasswordAPIRequest $request)
    {
        try {
            $passwordReset = $this->passwordResetsCodesRepository->findByCode($request->code);
            if (!$passwordReset) {
                return $this->sendApiError(__('passwords.code'), 404);
            }

            $user = $this->usersService->getByMobile($passwordReset->mobile);
            if (!$user) {
                return $this->sendApiError(__('passwords.user'), 404);
            }

            $this->usersService->update(array('password' => bcrypt($request->password)), $user->id);
            $passwordReset->update([
                'expired' => '1'
            ]);
            return $this->sendApiResponse(array(), __('passwords.reset'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
}
