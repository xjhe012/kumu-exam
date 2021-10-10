<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\RedisService;
use Illuminate\Support\Facades\Redis;
class UserController extends Controller
{

    public function index(UserService $UserService,RedisService $RedisService)
    {

        $data = $UserService->setDataForRedis();
        $formated_data = $RedisService->setUserInRedis($data);
        return response()->json($formated_data);
    }

    public function store(Request $request,UserService $UserService)
    {
        $user_info = $UserService->checkDuplicateEmail($request->email);
        if(!$user_info){
            $user = new User;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();
            return response()->json($user);
        }
        return response()->json($user_info);
    }

    public function show($user_name,UserService $UserService)
    {
        $UserService->setUserInRedis($user_name);
        $values = Redis::get('name:'.$user_name);

        return response()->json($values);
    }

}
