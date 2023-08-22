<?php
namespace App\Http\Controllers;

use DB;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{

    use ResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this-> userService = $userService;
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request);
            $token = $user->createToken('api-token')->plainTextToken;
            $data = [
                'user' => $user,
                'token' => $token,
                // 其他可能的數據
            ];
            return $this->responseSuccess($data, 'User Registered and Logged in Successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            if ($this->userService->attemptLogin($data)) {
                $user = $this->userService->getUser();
                $token = $user->createToken('api-token')->plainTextToken;
                $data = [
                    'user' => $user,
                    'token' => $token,
                ];
                return $this->responseSuccess($data, 'User Registered and Logged in Successfully!');
            }else{
                return $this->responseError(null, 'Invalid Email and Password!', Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        // 取得當前登入的使用者
        $user = $request->user();

        // 撤銷所有的令牌
        $user->tokens()->delete();

        // 回傳登出成功訊息
        return $this->responseSuccess(null, 'Logged out Successfully!');
    }

}
