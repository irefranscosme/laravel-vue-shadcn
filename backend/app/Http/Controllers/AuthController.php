<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(LoginRequest $request): JsonResponse  {

        $request->validated($request->all());

        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credentials not matched', 'data' => ''], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $this->createAccessToken($user, '7d');

        return response()->json(['message' => 'Logged in successfully', 'data' => [
            'user' => $user,
            'token' =>  $token,
        ]], 200);
    }

    public function register(RegistrationRequest $request): JsonResponse {

        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        $token = $this->createAccessToken($user, '7d');

        return response()->json(['message' => 'Registered Successfully', 'data' => [
            'user' => $user,
            'token' =>  $token,
        ]], 200);
    }

    public function logout() {
        Auth::guard('web')->logout();
        return response()->json(['message' => 'You are successfully logged out'], 200);
    }

    /**
     * @param string $expires Representing the duration time e.g 1s, 5m, 7h. of the expiry date
     */

    public function createAccessToken($user, $expires = ''): string {

        $token = $user->createToken('API Token of' . $user->name)->plainTextToken;

        if($expires) {
            $expiresAt = $this->addExpiry($expires);
            $personalAccessToken = $user->tokens()->latest()->first();
            $personalAccessToken->update(['expires_at' => $expiresAt]);
        }

        return $token;
    }

    public function addExpiry($value = ''): DateTime | null {

        if(!$value) return null;

        $time = (int) $value;
        $unit = substr($value, -1);

        if(strlen($value) > 2) return null;

        $expiresAt = now();

        switch($unit) {
            case 's':
                $expiresAt = now()->addSeconds($time);
                break;
            case 'S':
                $expiresAt = now()->addSeconds($time);
                break;
            case 'm':
                $expiresAt = now()->addMinutes($time);
                break;
            case 'M':
                $expiresAt = now()->addMinutes($time);
                break;
            case 'h':
                $expiresAt = now()->addHours($time);
                break;
            case 'H':
                $expiresAt = now()->addHours($time);
                break;
            case 'd':
                $expiresAt = now()->addDays($time);
                break;
            case 'D':
                $expiresAt = now()->addDays($time);
                break;
        }

        return $expiresAt;
    }
}
