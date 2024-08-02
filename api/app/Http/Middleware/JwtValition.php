<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtValition
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private $key;
    public function __construct()
    {
        $this->key = env('JWT');
    }
    public function handle(Request $request, Closure $next)
    {
        $EXITS = $this->GetToken($request);

        if (!$EXITS) {
            return res(['message' => 'Unauthenticated user'], 401);
        }
        $valid = $this->Valid($EXITS);
        if (!$valid) {
            return res(['message' => 'Invalid token'], 403);
        }
        $request->merge(['auth' => (array) $valid]);
        return $next($request);
    }
    public function GetToken($request)
    {
        $data = $request->header('Authorization');

        if (!$data) {
            return false;
        }

        $token = explode(' ', $data);
        return $token[1];
    }
    public function Valid($token)
    {
        $valid = explode('.', $token);

        if (count($valid) !== 3) {
            return false;
        }
        list($header, $payload, $sing) = explode('.', $token);
        $validSing = base64_encode(hash_hmac('sha256', $header . "." . $payload, $this->key, true));
        $DecPayload  = json_decode(base64_decode($payload));

        if ($validSing !== $sing) {
            return false;
        }
        if ($DecPayload->exp < time()) {
            return false;
        }
        return $DecPayload;
    }
}
