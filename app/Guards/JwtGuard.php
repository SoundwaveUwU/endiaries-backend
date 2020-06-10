<?php


namespace App\Guards;


use App\Session;
use App\User;
use Cache;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Log;

/**
 * @property Authenticatable|null user
 */
class JwtGuard implements Guard
{
    use GuardHelpers;

    private Session $session;
    private $decoded = null;

    public function __construct($provider)
    {
        $this->user = null;
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

        if (count(explode(' ', request()->header('Authorization'))) != 2)
            return null;

        $jwt = explode(' ', request()->header('Authorization'))[1];

        $publicKey = file_get_contents(config('jwt.keys.public'));

        try {
            $decoded = JWT::decode($jwt, $publicKey, [config('jwt.keys.type')]);
        } catch (Exception $e) {
            return null;
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
    protected function hasValidCredentials($user, $credentials)
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
}
