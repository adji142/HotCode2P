<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use App\Model\User2;
use App\Model\Loginusers;
use Carbon\Carbon;

class VerifyToken
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
        if($type == null) {
            return response()->json(['status' => 'error', 'message' => 'Anda bukan collector, sales maupun toko']);
        }

        try {
            if (! $token) {
                throw new \Exception('Access forbidden.');
            }

            if ( $type == 'toko') {
                $user = Loginusers::where("api_token",$token)->first();

                if (count($user) < 1) {
                    throw new \Exception('Invalid access token.');
                }

                $request->userinfo = $user;
            } else {
                $user = User2::where("api_token",$token)->first();

                if (count($user) < 1) {
                    throw new \Exception('Invalid access token.');
                }

                $request->userinfo = $user;
                $cari = User::where('kodesales', $request->userinfo->userid);
                if($cari->count() > 0) {
                    $hasil = $cari->first();
                    $request->userinfo->userid = $hasil->id;
                    $request->userinfo->typeuser = "sales";
                    $request->userinfo->recordownerid = $hasil->recordownerid;
                } else {
                    $cari = User::where('kodecollector', $request->userinfo->userid);
                    if($cari->count() > 0) {
                        $hasil = $cari->first();
                        $request->userinfo->userid = $hasil->id;
                        $request->userinfo->typeuser = "collector";
                        $request->userinfo->recordownerid = $hasil->recordownerid;
                    }
                }
            }
            

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return $next($request);
    }
}
