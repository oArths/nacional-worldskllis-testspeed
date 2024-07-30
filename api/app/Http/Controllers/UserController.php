<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class UserController extends Controller
{
    public function sing(Request $parms)
    {
        $newToken = new JwtCreation;

        $error = [];
        $email = $parms->email ?? $error[] = ['email' => 'email é necesario'];
        $passworld = $parms->password ?? $error[] = ['senha' => 'a senha é ncessaria'];
        $name = $parms->name ?? $error[] = ['name' => 'o nome é necessario'];
        $username = $parms->username ?? $error[] = ['username' => 'o username é necessario '];


        $validEmail = User::where('email', $email)->first();
        $validUsername = User::where('username', $username)->first();
        if (strlen($passworld) < 6) {
            $error[] = ['email' => 'a senha deve ter no minimo 6 caracteres'];
        }
        if ($validEmail) {
            $error[] = ['email' => 'email deve unico'];
        }
        if ($validUsername) {
            $error[] = ['username' => 'username deve unico'];
        }
        if ($error) {
            return res($error, 422);
        }
        $crip = hash('sha256', $passworld);


        $create = User::create([
            'name' => $name,
            'username' => $username,
            'password' => $crip,
            'email' => $email,
        ]);
        $token = $newToken->CreateToken($email);

        return res(['token' => $token], 201);
    }
    public function delete(Request $parms)
    {

        // return $parms->auth['user'];
        $email = $parms->auth['user'];
        $user = User::where('email', $email)->first();

        $access = AccessToken::where('userId', $user->id)->first();
        if ($access) {

            $access->delete();
        }

        return res([], 204);
    }
    public function signin(Request $parms)
    {
        $newToken = new JwtCreation;

        $error = [];
        $email = $parms->email ?? $error[] = ['email' => 'email é necesario'];
        $passworld = $parms->password ?? $error[] = ['senha' => 'a senha é ncessaria'];

        $encript =  hash("sha256", $passworld);

        $user = User::where('email', $email)->first();
        $userpass = User::where('password', $encript)->first();

        if (!$user) {
            $error[] = ['message' => 'Invalid email or password'];
        }
        if ($user) {
            if (strlen($user->password) !== 64) {
                $encript =  hash("sha256", $user->password);
                $user->password = $encript;
                $user->save();
            }
        }

        if (strlen($passworld) < 6) {
            $error[] = ['password' => 'a senha deve ter no minimo 6 caracteres'];
        }
        if ($user) {
            if ($encript !== $user->password || $email !== $userpass->email) {
                $error[] = ['message' => 'Invalid email or password'];
            }
        }
        
        
        
        if ($error) {
            return res([
                "message" => "Invalid properties",
                "erros" => $error
            ], 422);
        }
        $token = $newToken->CreateToken($email);
        return res(['token' => $token]);
    }
}
