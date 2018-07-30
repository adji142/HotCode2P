<?php

namespace App\Http\Middleware;

use Closure;

class VerifyApiKey
{
    protected $apikey;

    public function __construct(){

    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $api_key = $request->header('apikey');

        try {
            if (! $api_key) {
                throw new \Exception('Access forbidden.');
            } else {
                if($api_key != getenv("APP_KEY1")){
                    throw new \Exception('Wrong Api Key');
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }

        return $next($request);
    }
}
