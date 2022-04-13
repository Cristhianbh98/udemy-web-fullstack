<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;
use Illuminate\Support\Arr;

class JwtAuth {
  protected $key = ']*Aj[46F+ssX@d)!s4|GXTHL#(l~-435+9*e0U5;+ uB8Tni=6hQyQ;Gboh+v`-D';

  public function signup($email, $password) {
    $user = User::where(['email'=> $email])->first();

    if (!is_object($user)) throw new Exception('User does not exits.');
    
    $payload = array(
      'sub' => $user->id,
      'email' => $user->email,
      'name' => $user->name,
      'surname' => $user->surname,
      'iat' => time(),
      'exp' => time() + (60 * 24 * 60 * 60)
    );

    if (!password_verify($password, $user->password)) throw new Exception('Password is incorrect');

    $decoded = JWT::encode($payload, $this->key, 'HS256');

    return array(
      'token' => $decoded,
      'user' => array(
        'email' => $user->email,
        'name' => $user->name,
        'surname' => $user->surname
      )
    );
  }

  public function checkToken($token, $getIdentity = false) {
    $decoded = JWT::decode($token, new Key($this->key, 'HS256'));

    return array(
      'isValid' => is_object($decoded) || is_array($decoded),
      'user' => $getIdentity ? $decoded : ''
    );
  }
}
