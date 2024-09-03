<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class Authservices {
    public function login($credentials){
        // This method authenticates a user with their email and password. 
        //When a user is successfully authenticated, the Auth facade attempt() method returns the JWT token. 
        //The generated token is retrieved and returned as JSON with the user object

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json(['status' => 'error','message' => 'Unauthorized',], 401);
        }

        return $token;
    }
    //========================================================================================================================
    public function register($request){

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => 'user',
        ]);

        //أي مستخدم يسجل دخول سيكون دوره هو User لأن الأدمن له seeder
        //مع تحديد صلاحيات لهذا اليوزر
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $user->assignRole($userRole);

        $token = Auth::login($user);

        return [
            'token' => $token,
            'user' => $user,
        ];
    }
    //========================================================================================================================
    public function logout(){
        Auth::logout();
    }
    //========================================================================================================================
}
