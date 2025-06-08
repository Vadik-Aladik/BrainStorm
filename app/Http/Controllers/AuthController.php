<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePersonalRequest;
use App\Http\Requests\User\DeleteRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\Index\UserResultResource;
use App\Models\User;
use App\Models\UserTest;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('User/Login');
    }

    public function registration()
    {
        return Inertia::render('User/Registration');
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

        $resultUser = UserTest::where('user_id', auth()->id())->where('is_checked', 1)->with(['testGet', 'courseGet'])->orderBy('created_at', 'desc')->take(9)->get();
        $resourceResult = UserResultResource::collection($resultUser);

        return Inertia::render("User/Personal",[
            "role"=>$user->role,
            'result' => $resourceResult,
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

    public function delete(DeleteRequest $request)
    {
        $data = $request->validated();

        if(Auth::attempt([
            'email' => auth()->user()->email,
            'password' => $data['password'],
        ])){
            User::find(auth()->id())->delete();
            return response()->json([
                'delete_account' => true
            ]);
        }
        else{
            return response()->json([
                'delete_account' => false
            ]);
        }
    }
}
