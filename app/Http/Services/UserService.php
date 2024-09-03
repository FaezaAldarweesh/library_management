<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {
    
    public function getAllUsers(){
        return User::all();
    }
    //========================================================================================================================
    public function createUser($data){
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);
    }

    //========================================================================================================================
    public function updateCategory($data,User $user){
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);
        return $user;
    }

    //========================================================================================================================

    public function deleteUser(User $user)
    {
        //منع الأدمن من إزالة حسابه
        if ($user->hasRole('Admin')) {
            throw new \Exception('You cannot delete admin account');
        }
        $user->delete();
        return true;
    }


}
