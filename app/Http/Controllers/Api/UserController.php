<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserServices;
use App\Services\RedisServices;
use Illuminate\Support\Facades\Redis;
class UserController extends Controller
{

    public function index(UserServices $UserServices,RedisServices $RedisServices)
    {

        $data = $UserServices->setDataForRedis();
        $formated_data = $RedisServices->setUserInRedis($data);
        return response()->json($formated_data);
    }

    public function store(Request $request,UserServices $UserServices)
    {
        $user_info = $UserServices->checkDuplicateEmail($request->email);
        if(!$user_info){
            $user = new User;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();
            return response()->json($user);
        }
        return response()->json($user_info);
    }

    public function show($user_name,UserServices $UserServices)
    {
        $UserServices->setUserInRedis($user_name);
        $values = Redis::get('name:'.$user_name);

        return response()->json($values);
    }

}
