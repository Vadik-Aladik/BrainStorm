<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('User/Login');
    }
    
    public function signin(LoginRequest $request)
    {
        $data = $request->validated();
        if(auth()->attempt($data, true)){
            return response()->json([
                'login' => true
            ]);
        }
        return response()->json([
            'login' => false
        ]);
    }

    public function registration()
    {
        return Inertia::render('User/Registration');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        if($data['email'] == 'adminka@admin.ru'){
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'admin'
            ]);
            auth()->login($user, true);
            return response()->json([
                'data' => $data
            ]);
        }
        else{
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'student'
            ]);
            auth()->login($user, true);
            return response()->json([
                'data' => $data
            ]);
        }
    }
}
