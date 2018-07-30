<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Loginusers;
use App\Model\User;
use App\Model\User2;
use Carbon\Carbon;

class VerifyCollector
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
        if($type == null or ($type != 'collector' and $type != 'sales')) {
            return response()->json(['status' => 'error', 'message' => 'Anda bukan collector']);
        }

        try {
            if (! $token) {
                throw new \Exception('Access forbidden.');
            }

            $user = User2::where("api_token",$token)->first();

            if (count($user) < 1) {
                throw new \Exception('Invalid access token.');
            }

            $request->userinfo = $user;
            $cari = User::where('kodesales', $request->userinfo->userid);
            if($cari->count() > 0) {
                $hasil = $cari->first();
                $request->userinfo->userid = $hasil->id;
                $request->userinfo->recordownerid = $hasil->recordownerid;
                $request->userinfo->typeuser = "sales";
            } else {
                $cari = User::where('kodecollector', $request->userinfo->userid);
                if($cari->count() > 0) {
                    $hasil = $cari->first();
                    $request->userinfo->userid = $hasil->id;
                    $request->userinfo->recordownerid = $hasil->recordownerid;
                    $request->userinfo->typeuser = "collector";
                }
            }
            
            if($request->userinfo->typeuser != "collector" and $request->userinfo->typeuser != "sales"){
                throw new \Exception('You are not collector');
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return $next($request);
    }
}
