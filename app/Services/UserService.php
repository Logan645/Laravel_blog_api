<?php

namespace App\Services;


use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RegisterUserRequest;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepository->create($data);
        if (!$user) {
            throw new \Exception('User creation failed');
        }
        return $user;
    }

    public function attemptLogin(array $data)
    {
        return $this->userRepository->attempt($data);
    }

    public function getUser()
    {
        return $this->userRepository->getUser();
    }

    public function updateUser($id, $request): User|null
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])/',
        ]);

        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try{
            $result = $this->userRepository->update($id, $request);
        } catch (Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            throw new InvalidArgumentException("Unable to update user data",  Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        DB::commit();
        return $result;
    }
}
