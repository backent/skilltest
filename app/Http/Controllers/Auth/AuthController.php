<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Hash;
use Cookie;
use Auth;
use App\Support\Json;

class AuthController extends Controller
{

	public function register(Request $request) {
		$data = $request->validate([
            	'email' => 'required|email|max:50',
            	'password' => [
		            'required',
		            'min:6',
		            'confirmed',
		            'regex:/[a-z]/',
		            'regex:/[A-Z]/',
		            'regex:/[0-9]/',
		            'regex:/[@$!%*#?&]/',
		    	],
		    	'nohp'=> 'required|numeric'
        ]);

        $data['password'] = Hash::make($data['password']);

        $Account = User::getUserByEmail($data['email'])->first();

        if (isset($Account)) {
        	Json::set('message', 'Email already exists');
        	return response()->json(Json::get(), 409);
        } 

        $Account = User::create($data);
        Json::set('records', $Account);
        return response()->json(Json::get(), 201);
	}
    
    public function login(Request $request)
    {
            $data = $request->validate([
            	'email' => 'required|email|max:50',
            	'password' => [
		            'required',
		            'min:6',
		            'regex:/[a-z]/',
		            'regex:/[A-Z]/',
		            'regex:/[0-9]/',
		            'regex:/[@$!%*#?&]/',
		    	],

            ]);
            $Account = User::getUserByEmail($data['email'])->first();
            if (isset($Account)) {
				$isCorrect = Hash::check($data['password'], $Account->password);
				if ($isCorrect) {
            		$token = $Account->createToken('accesstoken')->accessToken;
                    $response = ['token' => $token];
                    $minutes = null; 
                    $path = null;
                    $domain = null;
                    $secure = null;
                    $httpOnly = true;
                    
                    return response()->json()->cookie('coin', $token, $minutes, $path, $domain, $secure, $httpOnly);
            	}   
            	else {
            		Json::set('message', 'Wrong Password');
            		return response()->json(Json::get(), 422);
            	}         	
            } else {
            	Json::set('message', 'Email Not Found');
            	return response()->json(Json::get(), 404);
            }
            
    }

    public function get_auth(Request $request)
    {
    	$cookie = Cookie::get('coin');

    	$response = [];
        $response['response']['records'] = $cookie;
        $response['response']['code'] = 200;
        $response['response']['description'] = 'OK';
        return response()->json($response,$response['response']['code']);
    }

    public function logout(Request $request) {
        $token= null;
        $minutes = null; 
        $path = null;
        $domain = null;
        $secure = null;
        $httpOnly = true;
        
        $revoke = Auth::user()->token()->revoke();

        Json::set('records', $revoke);
        return response()->json(Json::get(), 200)->cookie('coin', $token, $minutes, $path, $domain, $secure, $httpOnly);
    }//

   
}
