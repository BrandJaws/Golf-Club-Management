<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

    use \AuthToken,
        \ResponseProvider;

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        \Illuminate\Support\Facades\Log::error( __CLASS__,['request'=>$request->all()]);

    	if (!$request->has('auth_token')) {
            $this->error = 'not_authorized';
            return $this->response();
        }
        $authObject = self::validateAccessToken($request->get('auth_token'));
        if (!$authObject) {
            $this->error = 'invalid_auth_token';
            return $this->response();
        }
        $class = $authObject->resource_type;
        $user = $class::find($authObject->resource_id);
        Auth::login($user);
        return $next($request);
    }

}
