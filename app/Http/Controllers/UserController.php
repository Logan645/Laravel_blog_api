<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use ResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this-> userService = $userService;
    }

    public function user(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                throw new \Exception('未找到登录用户');
            }
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => '發生錯誤', 'error' => $e->getMessage()], 500);
        }
    }

    public function update($id, Request $request)
    {
        try{
            $data = $this->userService->updateUser($id, $request);
            if (is_null($data))
            return $this->responseError(null, 'User Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'User Updated Successfully');
        } catch (\Exception $exception){
            return $this->responseSuccess(null, $exception->getMessage(), $exception->getCode()? : Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
