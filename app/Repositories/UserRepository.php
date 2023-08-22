<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create($data):User|null
    {
        try {
            return $this->user->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function attempt(array $data):Bool
    {
        return Auth::attempt($data);
    }

    public function getUser():User|null
    {
        return Auth::user();
    }

    

    public function update($id, $request):User|null
    {
        $user = $this->user->find($id);

        if (is_null($user)){
            return null;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return $user;
    }

}
