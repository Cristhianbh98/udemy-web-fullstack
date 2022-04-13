<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Helpers\JwtAuth;
use Exception;

class UserController extends Controller {
  public function register(Request $request) {
    // validate data
    $validator = Validator::make($request->all(), array(
      'name' => ['required', 'max:255', 'regex:/^[A-Za-z ]+$/'],
      'surname' => ['required', 'max:255', 'regex:/^[A-Za-z ]+$/'],
      'email' => 'required|max:255|email|unique:users',
      'password' => 'required|min:12|max:50'
    ));

    if($validator->fails()) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'errors' => $validator->errors()
      );

      return response()->json($data, 400);
    }

    // encrypt password
    $password = password_hash($request->password, PASSWORD_BCRYPT, [ 'cost' => 4 ]);

    // create user
    $user = new User();
    $user->name = $request->name;
    $user->surname = $request->surname;
    $user->email = $request->email;
    $user->password = $password;
    $user->role = 'ROLE_USER';

    // save user
    $user->save();

    // response
    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'message' => 'User was created correctly.',
      'user' => $user
    );
    
    return response()->json($data, 200);
  }

  public function login(Request $request) {
    try {
      $jwtAuth = new JwtAuth();     
      $data_auth = $jwtAuth->signup($request->email, $request->password);

      $data = array(
        'status' => 'successfull',
        'code' => 200,
        'data' => $data_auth
      );
      
      return response()->json($data, 200);
    } catch(Exception $e) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => $e->getMessage()
      );

      return response()->json($data, 400); 
    }
  }

  public function checkToken(Request $request) {
    try {
      $jwtAuth = new JwtAuth();
      $dataJWT =  $jwtAuth->checkToken($request->token , $request->getIdentity);

      $data = array(
        'status' => 'successfull',
        'code' => 200,
        'data' => $dataJWT
      );
      
      return response()->json($data, 200);
    } catch(Exception $e) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => $e->getMessage()
      );

      return response()->json($data, 400); 
    }
  }
}
