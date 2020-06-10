<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountCreateRequest;
use App\Http\Requests\AccountLoginRequest;
use App\Http\Requests\AccountRefreshRequest;
use App\Session;
use App\User;
use Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\File;

class AccountController extends Controller
{
    public function index()
    {
        return Auth::user();
    }

    public function login(AccountLoginRequest $request)
    {
        if (!Auth::validate($request->validated()))
            abort(401, 'Incorrect credentials');

        $session = new Session();
        $session->generateNewRefreshToken();
        $session->fillSessionInfo();
        $session->user()->associate(Auth::user());
        $session->push();

        return [
            'access_token' => $session->generateNewAccessToken(),
            'refresh_token' => $session->refresh_token,
        ];
    }

    public function logout(AccountLoginRequest $request)
    {
        $session = Auth::session();

        Cache::put("session.{$session->id}.invalid", true, config('jwt.access.lifetime'));

        $session->delete();
    }

    public function refresh(AccountRefreshRequest $request)
    {
        $session = Session::where('refresh_token', $request->input('token'))
            ->firstOrFail();

        $result = [
            'access_token' => $session->generateNewAccessToken(),
        ];

        if ($session->shouldRegenerateRefreshToken()) {
            $session->generateNewRefreshToken();
            $session->save();
            $result['refresh_token'] = $session->refresh_token;
        }

        return $result;
    }

    public function store(AccountCreateRequest $request)
    {
        $user = User::create($request->validated());

        return ['user' => $user];
    }

    public function sessions()
    {
        return Auth::user()->sessions;
    }
}
