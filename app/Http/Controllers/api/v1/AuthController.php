<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckUserExistRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginWithMobileRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserInterface;
use App\Services\auth\CheckUserExistService;
use App\Services\auth\LoginService;
use App\Services\auth\LoginWithMobileService;
use App\Services\auth\RegisterService;
use App\Services\auth\UpdateProfileService;
use App\Services\SendOtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private $checkUserExistService;
    private $loginService;
    private $registerService;
    private $user;
    private $loginWithMobileService;
    private $updateProfileService;
    private $sendOtpService;

    /**
     * Create a new instance.
     * @param CheckUserExistService $checkUserExistService
     * @param LoginService $loginService
     * @param RegisterService $registerService
     * @param UserInterface $user
     * @param LoginWithMobileService $loginWithMobileService
     * @param UpdateProfileService $updateProfileService
     * @param SendOtpService $sendOtpService
     */
    public function __construct(
        CheckUserExistService $checkUserExistService,
        LoginService $loginService,
        RegisterService $registerService,
        UserInterface $user,
        LoginWithMobileService $loginWithMobileService, UpdateProfileService $updateProfileService, SendOtpService $sendOtpService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'loginWithMobile', 'register', 'SendOtpFunction', 'checkUserExist']]);
        $this->checkUserExistService = $checkUserExistService;
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->user = $user;
        $this->loginWithMobileService = $loginWithMobileService;
        $this->updateProfileService = $updateProfileService;
        $this->sendOtpService = $sendOtpService;
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->loginService->login($request->validated()['loginCredentials'], $request->validated()['fcmToken'] ?? null);
    }

    /**
     * Login With Mobile
     *
     * @param LoginWithMobileRequest $request
     * @return JsonResponse
     */
    public function loginWithMobile(LoginWithMobileRequest $request): JsonResponse
    {
        return $this->loginWithMobileService->loginWithMobile($request->validated()['mobile'], $request->validated()['fcmToken'] ?? null);
    }

    /**
     * API Register, on success return User Data
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->registerService->register($request->validated()['user'], $request->validated()['fcmToken'] ?? null);
    }

    /**
     * Check If User Exist Before
     *
     * @param CheckUserExistRequest $request
     * @return JsonResponse
     */
    public function checkUserExist(CheckUserExistRequest $request): JsonResponse
    {
        return $this->checkUserExistService->checkUserExist($request->validated());
    }

    /**
     * Send OTP Function
     *
     * @param SendOtpRequest $request
     * @return void
     */
    public function SendOtpFunction(SendOtpRequest $request)
    {
        $this->sendOtpService->SendOtpFunction($request->validated()['mobile'], $request->validated()['message']);
    }

    /**
     * Get User Data
     *
     * @return UserResource
     */
    public function me(): UserResource
    {
        $currentUserData = auth('api')->user();
        if ($currentUserData->hasRole('Customer')) {
            $currentUserData->load('Customer.Wallet');
        }
        return new UserResource($currentUserData);
    }

    /**
     * Update User Data
     *
     * @param UpdateUserRequest $request
     * @return UserResource
     */
    public function updateProfile(UpdateUserRequest $request): UserResource
    {
        return $this->updateProfileService->updateProfile($request->validated(), auth('api')->id());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return response()->json([
            'success' => true
        ]);
    }
}
