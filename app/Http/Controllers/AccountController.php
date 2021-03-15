<?php

namespace App\Http\Controllers;

use App\Guards\JwtGuard;
use App\Http\Requests\AccountCreateRequest;
use App\Http\Requests\AccountLoginRequest;
use App\Http\Requests\AccountRefreshRequest;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        return Auth::user();
    }

    public function login(AccountLoginRequest $request)
    {
        if (!Auth::validate($request->validated())) {
            if ($request->expectsJson()) {
                return response([
                    'message' => __('auth.failed'),
                    'errors' => [
                        'email' => [__('auth.failed')],
                    ]
                ], 422);
            }

            return redirect()->back()->withErrors([
                'email' => __('auth.failed'),
            ]);
        }

        $session = new Session();
        $session->generateNewRefreshToken();
        $session->fillSessionInfo();
        $session->user()->associate(Auth::user());
        $session->push();

        Cookie::queue(
            JwtGuard::REFRESH_COOKIE,
            $session->refresh_token,
            config('jwt.refresh.lifetime'),
            null,
            null,
            config('app.env') === 'production',
            false,
            false,
            'strict',
        );

        $accessToken = $session->generateNewAccessToken();
        Cookie::queue(
            JwtGuard::ACCESS_COOKIE,
            $accessToken,
            config('jwt.access.lifetime'),
            null,
            null,
            config('app.env') === 'production',
            false,
            false,
            'strict',
        );

        if ($request->expectsJson()) {
            return [
                'access_token' => $accessToken,
                'refresh_token' => $session->refresh_token,
            ];
        }

        return redirect()->intended(route('feed'));
    }

    public function logout(Request $request)
    {
        $session = Auth::session();

        Cache::put("session.{$session->id}.invalid", true, config('jwt.access.lifetime') * 60);

        $session->delete();

        Cookie::queue(JwtGuard::ACCESS_COOKIE, false, 0);
        Cookie::queue(JwtGuard::REFRESH_COOKIE, false, 0);

        if ($request->expectsJson()) {
            return ['logged_out' => true];
        } else {
            return redirect()->intended();
        }
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

    public function sessions(): AnonymousResourceCollection
    {
        return SessionResource::collection(
            Auth::user()->sessions()
                ->orderBy('created_at', 'desc')
                ->paginate()
        );
    }

    public function csrf(): array
    {
        return [
            'token' => csrf_token(),
        ];
    }
}
