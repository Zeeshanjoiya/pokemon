<?php

namespace App\Http\Controllers\APIs;

use Helper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{


    public static function debug($data)
    {
        echo "<pre>";
        print_r($data);
        die();
    }


    public function __construct(Request $request)
    {
        // Check API Status
        if (!Helper::GeneralWebmasterSettings("api_status")) {
            // API disabled
            exit();
        }

        Helper::SaveVisitorInfo(url()->current());
    }

    private function check_api_key($apiKey)
    {
        if (empty($apiKey)) {
            throw new \Exception('API key is missing', 500);
        }

        if ($apiKey != Helper::GeneralWebmasterSettings("api_key")) {
            throw new \Exception('Authentication failed', 401);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        // verifying the app_key
        $apiKey = $request->header('api-key');
        try {
            $this->check_api_key($apiKey);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        // validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }


        //compare passwords
        if($request->password != $request->confirm_password){
            return response()->json(['Passwords not matched' => $validator->errors()], 401);
        }


        // create user
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // create access token
            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['msg' => 'User created successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Login user and create token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // verifying the app_key
        $apiKey = $request->header('api-key');
        try {
            $this->check_api_key($apiKey);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('MyApp')->accessToken;
        
            // $expiresAt = null;
            // $userTokens = $user->tokens();

            // if ($userTokens->isNotEmpty()) {
            //     $expiresAt = $userTokens->first()->expires_at->getTimestamp();
            // }
        
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                //'expires_in' => $expiresAt,
                'user' => $user
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
