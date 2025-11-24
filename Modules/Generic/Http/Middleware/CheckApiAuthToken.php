<?php

namespace Modules\Generic\Http\Middleware;

use Modules\Access\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckApiAuthToken
{
    /**
     * The URIs that should be excluded from Auth Token verification.
     *
     * @var array
     */
    protected $except = ['api/login', 'api/register'];
    private $user = NULL;

    public function __construct(Request $request)
    {

    }

    public function handle($request, Closure $next)
    {
//        if (!$this->inExceptArray($request)) {
//            if ($request->api_token) {
//                $api_token = Cache::tags('user')->remember($request->api_token, 60 * 24 * 4, function () use ($request) {
//                    return User::where('api_token', $request->api_token)->value('api_token') ? true : null;
//                });
//                if (is_null($api_token)) {
//                    Cache::tags('user')->forget($request->api_token);
//                } else {
//                    if (!Auth::check()) {
//                        $this->user=User::where('api_token', $request->api_token)->first();
//                        Auth::loginUsingId($this->user->id,true);
//                    }else{
//                        $this->user=Auth::user();
//                    }
//                     $api_token == $request->api_token ? $api_token : NULL;
//                }
//            }
//            if (is_null($this->user))
//                throw new AuthenticationException();
//
//        }
        if (!$this->inExceptArray($request)) {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        }

        return $next($request);
    }

    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }
        return false;
    }

}
