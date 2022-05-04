<?php

namespace App\Http\Controllers;

use App\Models\MyUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{

    public function index(Request $request) {
        $request->session()->put("name", "anubhav");
        $value = $request->session()->get("name");

        Redis::set("name","anubhav");
        $name = Redis::get("name");

        //DELETE ONE KEY FROM SESSION
        // $request->session()->forget("name");

        //DELETE ENTIRE SESSION
        // $request->session()->flush();

        return ["session", $name];
        // Log::info($request);
        // return ["message" => "jhkjl;"];
    }

    public function response($user)
    {
        $token = $user->createToken(str()->random(40))->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required','min:5'],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:10'],
            ]
        );
        if($validator->fails()) {
            return ["message" => "Validation failed"];
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return $user;
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );
        if($validator->fails()) {
            return ["message" => "invalid creds"];
        }
        $user = User::where("email",$request->email)->first();
        Log::info($user);
        return $this->response($user);
        // $cred = $request->validate([
        //     'email' => 'required|email|exists:users',
        //     'password' => 'required'
        // ]);
        // if(!Auth::attempt($cred)) {
        //     return response()->json([
        //         'message' => 'unauthenticated request'
        //     ],401);
        // }
        // return $this->response(Auth::user());
    }
}
