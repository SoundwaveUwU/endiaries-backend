<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Cache;
use Firebase\JWT\JWT;

/**
 * @property Carbon expires_at
 * @property string refresh_token
 * @property string device
 * @property string platform
 * @property string browser
 */
class Session extends Model
{
    protected $fillable = [
        'refresh_token',
        'device',
        'platform',
        'browser',
    ];

    protected $hidden = [
        'refresh_token',
    ];

    protected $dates = [
        'created_at',
        'expires_at',
    ];

    protected $appends = [
        'created_at_human_readable'
    ];

    public function getCreatedAtHumanReadableAttribute()
    {
        if (!isset($this->attributes['created_at']))
            return null;

        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shouldRegenerateRefreshToken()
    {
        return now()->timestamp + config('jwt.refresh.lifetime') / 2 > $this->expires_at->timestamp;
    }

    public function generateNewRefreshToken()
    {
        $this->refresh_token = Str::random(config('jwt.refresh.length'));
        $this->expires_at = now()->addSeconds(config('jwt.refresh.lifetime'));
    }

    public function generateNewAccessToken()
    {
        $privateKey = file_get_contents(config('jwt.keys.private'));

        $payload = [
            'iss' => config('jwt.issued_by'),
            'aud' => config('jwt.audience'),
            'iat' => now()->timestamp,
            'nbf' => now()->timestamp,
            'exp' => now()->addSeconds(config('jwt.access.lifetime'))->timestamp,
            'sid' => $this->id,
            'uid' => $this->user->id,
        ];

        return JWT::encode($payload, $privateKey, config('jwt.keys.type'));
    }

    public function banSession()
    {
        Cache::put("session.{$this->id}.invalid", true, config('jwt.access.lifetime'));
    }

    public function fillSessionInfo()
    {
        $agent = new Agent();

        $device = 'other';
        $platform = 'other';
        $browser = 'other';

        switch (true) {
            case $agent->isDesktop():
                $device = 'desktop';
                break;
            case $agent->isTablet():
                $device = 'tablet';
                break;
            case $agent->isMobile():
                $device = 'phone';
                break;
        }

        switch (true) {
            case $agent->isDesktop():
                $device = 'desktop';
                break;
            case $agent->isTablet():
                $device = 'tablet';
                break;
            case $agent->isMobile():
                $device = 'phone';
                break;
        }

        switch (true) {
            case $agent->is('Windows'):
                $platform = 'windows';
                break;
            case $agent->is('OS X') || $agent->is('iOS') || $agent->is('iPad OS'):
                $platform = 'apple';
                break;
            case $agent->is('Android'):
                $platform = 'android';
                break;
            case $agent->is('Linux'):
                $platform = 'linux';
                break;
        }

        switch (true) {
            case $agent->is('Firefox'):
                $browser = 'firefox';
                break;
            case $agent->is('Edge'):
                $browser = 'edge';
                break;
            case $agent->is('Chrome'):
                $browser = 'chrome';
                break;
            case $agent->is('Safari'):
                $browser = 'safari';
                break;
            case $agent->is('Internet Explorer'):
                $browser = 'internet-explorer';
                break;
        }

        $this->device = $device;
        $this->platform = $platform;
        $this->browser = $browser;
    }
}
