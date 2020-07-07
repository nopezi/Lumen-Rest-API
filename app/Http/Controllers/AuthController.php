<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\ResponseHandler;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class AuthController extends Controller
{
    private $user;
    private $respHandler;

    public function __construct()
    {
        $this->user = new User();
        $this->respHandler = new ResponseHandler();
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|string|email'
        ]);

        if ($validate->fails()) {return $this->respHandler->validateError($validate->errors());}

        $input = $request->all();
        
        if (!$this->user->where('username', $input['username'])->where('email', $input['email'])->first()) {

            $input['password'] = Hash::make($input['password']);
            $user = $this->user->create($input);
            return $this->respHandler->send(201, "Register Success", new UserResource($user));
        } else {
            return $this->respHandler->exists("Users");
        }
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required'
        ]);

        if ($validate->fails()) {return $this->respHandler->validateError($validate->errors());}

        $input = $request->all();

        $user = $this->user->where('username', $input["username"])->first();
        if ($user) {
            if (Hash::check($input['password'], $user->password)) {
                return $this->respHandler->send(200, "Berhasil login", new UserResource($user));
            } else {
                return $this->respHandler->badCredentials();
            }
        } else {
            return $this->respHandler->notFound("Users");
        }
    }
}
