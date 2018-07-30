<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Loginusers;
use Carbon\Carbon;

class VerifyToko
{

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
        
        $token = $request->header('accesstoken');
        $type = $request->header('type');
        if($type == null or $type != 'toko') {
            return response()->json(['status' => 'error', 'message' => 'Anda bukan toko']);
        }

        try {
            if (! $token) {
                throw new \Exception('Access forbidden.');
            }

            $user = Loginusers::where("api_token",$token)->first();

            if (count($user) < 1) {
                throw new \Exception('Invalid access token.');
            }

            $request->userinfo = $user;

            if($user->typeuser != "toko"){
                throw new \Exception('You are not toko');
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return $next($request);
    }
}
