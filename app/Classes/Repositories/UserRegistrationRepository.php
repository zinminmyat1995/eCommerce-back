<?php

namespace App\Classes\Repositories;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\UserRegistrationInterface;

class UserRegistrationRepository implements UserRegistrationInterface
{
    function __construct()
    {
    }

	function login($code,$password){
		$res = Admin::where('deleted_at', null)->where('code', $code)->get()->toArray();
		if(count($res) > 0 ){
			$res1 = Admin::where('deleted_at', null)->where('code', $code)->where('password', $password)->get()->toArray();
			if(count($res1) > 0 ){
				return $res1;
			}else{
				return "Your password is incorrect!";
			}
	
		}else{
			return "Your account is not registered!";
		}
        
       
	}

	function  save($name, $email, $phone_no,$address,$password,$login_id){
		$res = User::where('deleted_at', null)->get()->toArray();
		$data = [
			"code" => 10000 + count($res) + 1,
			"name" => $name,
			"email" => $email,
			"phone_no" => $phone_no,
			"address" => $address,
			"password" => $password,
			"created_id" => $login_id,
			"updated_id" => $login_id,
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];

        $shopInsert = User::insert($data);
        
        return true;
    }

	function resetPassword($code, $password, $login_id ){
		$results = User::where('deleted_at', null)
        ->where('code',$code)
        ->update([
            'password' => $password,
			"updated_id" => $login_id,
            "updated_at"     => now()
        ]);

        return $results;
	}

	
}
