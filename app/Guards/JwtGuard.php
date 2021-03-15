<?php


namespace App\Guards;


use App\Models\Session;
use App\Models\User;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @property Authenticatable|null user
 */
class JwtGuard implements Guard, StatefulGuard
{
    use GuardHelpers;

    private ?Session $session;

    /**
     * @var object|null
     */
    private ?object $decoded = null;

    const REFRESH_COOKIE = 'endiaries_refresh';
    const ACCESS_COOKIE = 'endiaries_access';

    public function __construct($provider)
    {
        $this->user = null;
        $this->session = null;
        $this->setProvider($provider);
    }

    /**
     * @inheritDoc
     */
    public function check()
    {
        return !is_null($this->user());
    }

    /**
     * @inheritDoc
     */
    public function guest()
    {
        return is_null($this->user());
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        if (!is_null($this->user))
            return $this->user;

        if (!is_null($this->id()))
            $this->setUser(User::find($this->id()));

        return $this->user;
    }

    public function session()
    {
        if (!is_null($this->session))
            return $this->session;

        $this->session = Session::find($this->decodeJWT()->sid);

        return $this->session;
    }

    /**
     * @inheritDoc
     */
    public function id()
    {
        return $this->decodeJWT()->uid ?? null;
    }

    private function decodeJWT()
    {
        if (!is_null($this->decoded))
            return $this->decoded;

        $decoded = null;

        $publicKey = file_get_contents(config('jwt.keys.public'));

        if (Cookie::has(self::ACCESS_COOKIE)) {
            $cookie = Cookie::get(self::ACCESS_COOKIE);
            $jwt = Str::replaceFirst('Bearer ', '', $cookie);

            try {
                return JWT::decode($jwt, $publicKey, [config('jwt.keys.type')]);
            } catch (ExpiredException $e) {

                if (!Cookie::has(self::REFRESH_COOKIE))
                    return null;

                $decoded = $this->refreshTokens($publicKey);

            } catch (Exception $e) {
                return null;
            }
        }

        if (Cookie::has(self::REFRESH_COOKIE)) {
            $decoded = $this->refreshTokens($publicKey);
        }

        if (is_null($decoded)) {
            if (count(explode(' ', request()->header('Authorization'))) != 2) {
                return null;
            }

            $jwt = explode(' ', request()->header('Authorization'))[1];

            try {
                $decoded = JWT::decode($jwt, $publicKey, [config('jwt.keys.type')]);
            } catch (Exception $e) {
                return null;
            }
        }

        if (Cache::has("session.{$decoded->sid}.invalid"))
            return null;

        if ($decoded->iss != config('jwt.issued_by'))
            return null;

        if ($decoded->aud != config('jwt.audience'))
            return null;

        $this->decoded = $decoded;

        return $decoded;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        $result = $this->hasValidCredentials($user, $credentials);

        if ($result)
            $this->setUser($user);

        return $result;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, array $credentials)
    {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * @inheritDoc
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $publicKey
     * @return object|null
     */
    private function refreshTokens(string $publicKey)
    {
        /** @var Session $session */
        $session = Session::where('refresh_token', Cookie::get(self::REFRESH_COOKIE))
            ->first();

        if (is_null($session)) {
            Cookie::queue(self::REFRESH_COOKIE, null);
            return $session;
        }

        $jwt = $session->generateNewAccessToken();
        Cookie::queue(
            self::ACCESS_COOKIE,
            $jwt,
            config('jwt.access.lifetime'),
            null,
            null,
            config('app.env') === 'production',
            true,
            false,
            'strict',
        );

        if ($session->shouldRegenerateRefreshToken()) {
            $session->generateNewRefreshToken();
            $session->save();

            Cookie::queue(
                self::REFRESH_COOKIE,
                $session->refresh_token,
                config('jwt.refresh.lifetime'),
                null,
                null,
                config('app.env') === 'production',
                false,
                false,
                'strict',
            );
        }

        try {
            return JWT::decode($jwt, $publicKey, [config('jwt.keys.type')]);
        } catch (Exception $e) {
            return null;
        }
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);

            return true;
        }

        return false;
    }

    public function once(array $credentials = [])
    {
        // TODO: Implement once() method.
    }

    public function login(Authenticatable $user, $remember = false)
    {
        // TODO: Implement login() method.
    }

    public function loginUsingId($id, $remember = false)
    {
        // TODO: Implement loginUsingId() method.
    }

    public function onceUsingId($id)
    {
        // TODO: Implement onceUsingId() method.
    }

    public function viaRemember()
    {
        // TODO: Implement viaRemember() method.
    }

    public function logout()
    {
        // TODO: Implement logout() method.
    }
}
