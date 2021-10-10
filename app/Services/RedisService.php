<?php

namespace App\Services;
use Illuminate\Support\Facades\Redis;
class RedisService {


    public function setUserInRedis($data)
    {
        foreach($data as $key => $val){
            Redis::hmset("githubusers:".$val->login, "login:".$val->login, $val->login, "company:".$val->company, 
                        $val->company,'followers_count:'.$val->followers_count, $val->followers_count
                        ,'public_repo_count:'.$val->public_repo_count, $val->public_repo_count);
            Redis::expire("githubusers:".$val->login, 2);
        }
     
        $values = Redis::HGETALL('githubusers:'.$val->login);

        return $values;
        
    }
}