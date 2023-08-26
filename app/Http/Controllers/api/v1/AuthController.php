<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckOtpRequest;
use App\Http\Requests\CheckUserExistRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginWithMobileRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Interfaces\UserInterface;
use App\Services\auth\CheckUserExistService;
use App\Services\auth\LoginService;
use App\Services\auth\LoginWithMobileService;
use App\Services\auth\RegisterService;
use App\Services\auth\UpdateProfileService;
use App\Services\FirebaseService;
use App\Services\SendOtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthController extends Controller
{
    private $checkUserExistService;
    private $loginService;
    private $registerService;
    private $user;
    private $loginWithMobileService;
    private $updateProfileService;
    private $sendOtpService;
    private $firebaseService;
    // protected $auth;

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
        LoginWithMobileService $loginWithMobileService,
        UpdateProfileService $updateProfileService,
        SendOtpService $sendOtpService,
        FirebaseService $firebaseService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'loginWithMobile', 'register', 'SendOtpFunction', 'CheckOtpFunction', 'checkUserExist', 'firebaseLogin']]);
        $this->checkUserExistService = $checkUserExistService;
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->user = $user;
        $this->loginWithMobileService = $loginWithMobileService;
        $this->updateProfileService = $updateProfileService;
        $this->sendOtpService = $sendOtpService;
        $this->firebaseService = $firebaseService;
        // $this->auth = Firebase::auth();
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
        return $this->registerService->register($request->validated()['user'], $request->validated()['fcmToken'] ?? null, $request->file('image') ?? null);
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

    public function refresh(){
        
    }

    /**
     * Send OTP Function
     *
     * @param SendOtpRequest $request
     * @return void
     */
    public function SendOtpFunction(SendOtpRequest $request)
    {
        return $this->sendOtpService->SendOtpFunction($request->validated()['mobile'] ?? $request->validated()['email']);
    }
    public function checkOtpFunction(CheckOtpRequest $request)
    {
        return $this->sendOtpService->checkOtpFunction($request->validated()['mobile'] ?? $request->validated()['email'], $request->validated()['otp']);
    }
    public function firebaseLogin(Request $request)
    {
        $token = $request->validate(['token' => 'required'])['token'];
        return $this->firebaseService->authenticateFirebase($token);
        // $response = (object) []; //
        // $response->token = $token;
        // $auth = app('firebase.auth');
        // try {
        //     $verifiedIdToken = $auth->verifyIdToken($token);
        // }catch (FailedToVerifyToken $e) {
        //     return response()->json('The token is invalid: '. $e->getMessage());
        // }
        // $email = $verifiedIdToken->claims()->get('email');
        // $response->payload_email = $email;
        // $uid = $verifiedIdToken->claims()->get('sub');
        // $response->payload_uid = $uid;

        // $user = $auth->getUser($uid);
        // $response->authenticated_user = $user;

        // return response()->json($response, 200);
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
            $currentUserData->load('Customer.Wallet', 'Skill');
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
        return $this->updateProfileService->updateProfile($request->validated(), auth('api')->id(), $request->file('image') ?? null);
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
