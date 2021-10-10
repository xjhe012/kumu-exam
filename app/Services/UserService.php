<?php

namespace App\Services;
use App\User;
use Illuminate\Support\Facades\Redis;
class UserService {
    public function __construct(User $user)
    {
        $this->UserModel = $user;
    }
    public function checkDuplicateEmail($params)
    {
          return  $this->UserModel->checkUserinfo('email',$params);
    }

    public function getGitHubUser()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.github.com/users",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 24047408-e12d-4971-f67f-9774a0879106",
            "User-Agent:users"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    public function getGitHubUserRepo($username)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.github.com/users/".$username.'/repos',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 24047408-e12d-4971-f67f-9774a0879106",
            "User-Agent:". $username
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    public function getGitHubUserFollowers($username)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.github.com/users/".$username.'/followers',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 24047408-e12d-4971-f67f-9774a0879106",
            "User-Agent:". $username
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    public function getGitHubUserByUserName($params)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.github.com/users/mojombo",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 24047408-e12d-4971-f67f-9774a0879106",
            "User-Agent:". $params
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    public function setDataForRedis()
    {
        $data = array();
        $github_user = $this->getGitHubUser();
        $decoded_data = $github_user;
        foreach($decoded_data as $key => $val){  
          
            $user_info= $this->getGitHubUserByUserName($val->login);
            $user_repo = $this->getGitHubUserRepo($val->login);
            $followers = $this->getGitHubUserFollowers($val->login);    
            $count_repo = count($user_repo);
            $count_followers = count($followers);
            $data[$key]['name'] = $user_info->name;
            $data[$key]['login'] = $user_info->login;
            $data[$key]['company'] =$user_info->company ;
            $data[$key]['followers_count'] = $count_followers;
            $data[$key]['public_repo_count'] = $count_repo;
        }

        return $data;
    }

}