<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRegistrationInterface;
use App\Classes\Repositories\UserRegistrationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserRegistrationController extends Controller
{
    private UserRegistrationRepository $repository;
    public function __construct(UserRegistrationInterface $repository)
    {
        $this->repository = $repository;
    }

    
    public function login(Request $request)
    {

        $code = $request->code;
        $password = $request->password;
        $rules = [
            'code' => 'required',
            "password" => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->login($code,$password);
        if (is_array($res)){
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => $res], 200);
        }
       
    }


    public function register(Request $request)
    {

        $name = $request->name;
        $email = $request->email;
        $phone_no = $request->phone_no;
        $address = $request->address;
        $password = $request->password;
        $login_id = $request->login_id;
        $rules = [
            'name' => 'required',
            "email" => 'required',
            'phone_no' => 'required',
            "address" => 'required',
            "password" => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->save($name, $email, $phone_no,$address,$password,$login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Fail to save data!"], 200);
        }
       
    }

    public function reset_password(Request $request)
    {

        $code = $request->code;
        $password = $request->password;
        $login_id = $request->login_id;
        $rules = [
            'code' => 'required',
            "password" => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->resetPassword($code, $password, $login_id );

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Update Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Your code is not exists!"], 200);
        }
       
    }

    
}
