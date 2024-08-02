<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\User;

class JwtCreation extends Controller
{
    private $key;
    public function __construct()
    {
        $this->key = env('JWT');
    }
    public function CreateToken($parms)
    {

        $header = [
            'Typ' => 'JWT',
            'alg' => 'HS256',
        ];

        $now = time();
        $pyload = [
            'exp' => $now + 3600,
            'now' => $now,
            'user' => $parms
        ];

        $pyload = base64_encode(json_encode($pyload));
        $header = base64_encode(json_encode($header));


        $sing = base64_encode(hash_hmac('sha256', $header . "." . $pyload, $this->key, true));

        $token = "Bearer " . $header . "." . $pyload . "." . $sing;

        $user = User::where('email', $parms)->first();
        AccessToken::create([
            'userId' => $user->id,
            'tokenString' =>  $header . "." . $pyload . "." . $sing,

        ]);
        return $token;
    }
}
