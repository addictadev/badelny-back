<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\ContactUsAPIRequest;
use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\LoginAPIRequest;
use App\Http\Requests\API\ReviewAPIRequest;
use App\Http\Requests\API\UpdatePasswordAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\SellerReviewResource;
use App\Http\Resources\UserResource;
use App\Models\ContactUs;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SellerReview;
use App\Models\TermsConditions;
use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Repositories\MobileVerificationsRepository;
use App\Repositories\ProductRepository;
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

    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;

    private MobileVerificationsRepository $mobileVerificationsRepository;

    /** @var  UsersService */
    private $usersService;

    public function __construct(UserRepository $userRepo, UsersService $usersService, CategoryRepository $categoryRepo, ProductRepository $productRepo, MobileVerificationsRepository $mobileVerificationsRepo)
    {
        $this->userRepository = $userRepo;
        $this->usersService = $usersService;
        $this->categoryRepository = $categoryRepo;
        $this->productRepository = $productRepo;
        $this->mobileVerificationsRepository = $mobileVerificationsRepo;
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
            $fullMobileNumber = $request->calling_code . $request->phone;
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $phoneNumber = $phoneUtil->parse($fullMobileNumber , null , null , true);
            $isValid = $phoneUtil->isValidNumber($phoneNumber);
            $phoneNumberFormated = $phoneUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164);
            if (!$isValid) {
                return $this->sendApiError(__('auth.phoneNotValid') , 500);
            }
            $mobileVerify = $this->mobileVerificationsRepository->findByPhoneVerify($phoneNumberFormated);
            if (!$mobileVerify) {
                return $this->sendApiResponse(array('data' => ['is_verification' => 0]), __('messages.Mobile_Not_Verify'));
            }
            $mobileExit = $this->usersService->getByMobile($phoneNumberFormated);
            if ($mobileExit) {
                return $this->sendApiError(__('messages.Mobile_In_Use'), 422);
            }

            $user = $this->usersService->register($phoneNumberFormated, $request);
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
            $response = array('user' => $user, 'is_verification' => 1, 'token' => $token);
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

            $data = ['user' => $user, 'is_verification' => 1, 'token' => $token->accessToken];
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

    public function getHome()
    {
        try {
            $user_id = \request()->user() ? \request()->user()->id : null;
            $user = $this->usersService->getInfo($user_id);
            $limit = \request('limit') ? \request('limit') : 20;
           $category =\request('category_id') ? \request('category_id') : null;
           $search =\request('search') ? \request('search') : null;
            $products = $this->productRepository->getHomeProducts($limit,$category,$search);

            $response = array(
                'data' => [
                    'user' => $user ? new UserResource($user) : null,
                    'products' => ProductResource::collection($products),
                ],
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

    public function myInterestedCategories()
    {
        try {
            $user = $this->usersService->getInfo($this->getUser()->id);
            $response = array(
                'data' => CategoryResource::collection($user->interestCategories),
            );

            return $this->sendApiResponse($response, __('messages.retrieved_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function termsConditions()
    {
        try {
            $termsConditions = TermsConditions::query()->first();
            $response = array(
                'data' => $termsConditions,
            );

            return $this->sendApiResponse($response, __('messages.retrieved_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function issuesTypes()
    {
        try {
            $response = array(
                'data' => array(
                    [
                        'id' => 1,
                        'name' => 'Lorem ipsum'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Lorem ipsum 2'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Lorem ipsum 3'
                    ]
                )
            );

            return $this->sendApiResponse($response, __('messages.retrieved_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function contactUs(ContactUsAPIRequest $request)
    {
        try {
            $user = $this->usersService->getById($this->getUser()->id);
            if (!$user) {
                return $this->sendApiError(__('passwords.user'), 404);
            }

            $contactUs = ContactUs::query()->create([
                'user_id' => $this->getUser()->id,
                'phone' => $request->phone,
                'issue_type' => $request->issue_type,
                'message' => $request->message,
            ]);

            if($request->hasFile('images')){
                $contactUs->addMultipleMediaFromRequest(['images'])
                    ->each(function ($contactUs) {
                        $contactUs->toMediaCollection('contact_images');
                    });
            }

            return $this->sendApiResponse(array(), __('messages.update_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function deleteAccount()
    {
        try {
            /** @var User $user */
            $user = $this->usersService->getById($this->getUser()->id);

            if (empty($user)) {
                return $this->sendApiError(__('passwords.user'), 404);
            }

            $user->delete();

            return $this->sendApiResponse(array(), __('messages.retrieved_successfully'));
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
    public function getFavourites()
    {
        try{
            $user_id = \request()->user() ? \request()->user()->id : null;

        $products = $this->productRepository->getFavouriteProducts($user_id);

         $response = array(
             'data' => ProductResource::collection($products),
         );
        return $this->sendApiResponse($response, __('messages.retrieved_successfully'));

            } catch (\Exception $e) {

         return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function storeReview(ReviewAPIRequest $request)
    {
        try {


            // save product review
            $productReview = new ProductReview();
            $productReview->user_id = $request->user_id;
            $productReview->product_id = $request->product_id;
            $productReview->rate = $request->product_rate;
            $productReview->save();

            // get the product review avg

            $avgProduct = ProductReview::query()->where('product_id', $request->product_id)->avg('rate');
            $productAvg = number_format((float)$avgProduct, 1, '.', '');
            // update product column with new rate
            Product::where('id',$request->product_id)->update([
                'rate' => $productAvg
            ]);

            // save seller review
            $sellerReview = new SellerReview();
            $sellerReview->user_id = $request->user_id;
            $sellerReview->seller_id = $request->seller_id;
            $sellerReview->rate = $request->seller_rate;
            $sellerReview->save();

            // get the seller review avg

            $avgSeller = SellerReview::query()->where('seller_id', $request->seller_id)->avg('rate');
            $sellerAvg = number_format((float)$avgSeller, 1, '.', '');
            // update seller column with new rate
            User::where('id',$request->seller_id)->update([
                'rate' => $sellerAvg
            ]);

            return $this->sendApiResponse(array(), 'Review saved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
    public function getProductReview($id)
    {
        try {
            $productReview = ProductReview::where('product_id',$id)->get();

            return $this->sendApiResponse(array('data' => ProductReviewResource::collection($productReview)), __('messages.retrieved_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    public function getSellerReview($id)
    {
        try {
            $sellerReview = SellerReview::where('seller_id',$id)->get();
            return $this->sendApiResponse(array('data' => SellerReviewResource::collection($sellerReview)), __('messages.retrieved_successfully'));
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
}
