<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePersonalRequest;
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
                'login' => true,
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
                'auth' => true
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
                'auth' => true
            ]);
        }
    }

    public function personal()
    {
        $user = auth()->user();
        return Inertia::render("User/Personal",[
            "role"=>$user->role
        ]);
    }

    public function personalData()
    {
        $user=User::where('id', auth()->id())->get();

        return response()->json([
            'user' => $user,
        ]);
    }

    public function personalDataChange(ChangePersonalRequest $request)
    {
        $data=$request->validated();

        if($data['password']==null || $data['password']==''){
            User::where('id', auth()->id())->update([
                'name'=>$data['name'],
                'email'=>$data['email'],
            ]);
            // return response()->json([
            //     'data' => $data,
            //     'change_password?' => false,
            // ]);
        }
        else{
            User::where('id', auth()->id())->update([
                'name'=>$data['name'],
                'email'=>$data['email'],
                'password'=>Hash::make($data['password']),
            ]);
            // return response()->json([
            //     'data' => $data,
            //     'change_password?' => true,
            // ]);
        }

        return response()->json([
            'result' => true
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'logout'=>True
        ]);
    }
}
