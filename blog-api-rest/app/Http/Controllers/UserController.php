<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Helpers\JwtAuth;
use Exception;
use Illuminate\Support\Arr;

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

  public function update(Request $request) {
    $jwtAuth = new JwtAuth();
  
    // Get user
    $user = $jwtAuth->checkToken($request->token, true);

    // Validate fields
    $validator = Validator::make($request->all(), array(
      'name' => ['max:255', 'regex:/^[A-Za-z ]+$/'],
      'surname' => ['max:255', 'regex:/^[A-Za-z ]+$/'],
      'email' => 'max:255|email|unique:users,email,' . $user->sub,
      'password' => 'min:12|max:50'
    ));

    if($validator->fails()) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'errors' => $validator->errors()
      );

      return response()->json($data, 400);
    }

    $updateData = array();

    if (boolval($request->name)) $updateData['name'] = $request->name;
    if (boolval($request->surname)) $updateData['surname'] = $request->surname;
    if (boolval($request->email)) $updateData['email'] = $request->email;

    if (boolval($request->password)) {
      $updateData['password'] = password_hash($request->password, PASSWORD_BCRYPT, [ 'cost' => 4 ]);
    }

    User::where('id', $user->sub)->update($updateData);

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'message' => 'User updated'
    );

    return response()->json($data, 200);
  }

  public function profile(Request $request) {
    $user = User::find($request->id);

    if (!is_object($user)) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => 'User not found'
      );

      return response()->json($data, 400);
    }

    $user->image = 'https://thumbs.dreamstime.com/b/default-avatar-profile-vector-user-profile-default-avatar-profile-vector-user-profile-profile-179376714.jpg';

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $user
    );

    return response()->json($data, 200);
  }

  public function checkToken(Request $request) {
    $jwtAuth = new JwtAuth();
    $isValid =  $jwtAuth->checkToken($request->token);

    if (!$isValid) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => 'Bad token'
      );

      return response()->json($data, 400);
    }

    $dataJWT = $jwtAuth->checkToken($request->token, $request->getIdentity);

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => null
    );

    if (boolval($request->getIdentity)) $data['data']['user'] = $dataJWT;
    else $data['data']['isValid'] = $dataJWT;
    
    return response()->json($data, 200);
  }
}
