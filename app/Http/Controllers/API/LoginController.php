<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Config;
use Validator;
use App\Model\User;
use App\Model\User2;
use App\Model\Loginusers;
use App\Model\Shop;
use Hash;
use Illuminate\Support\Facades\Crypt;
class LoginController extends Controller{
    public function checkConnection(Request $request){
        
            $params = $request->all();
            $params['password']         = $params['password'];
            $params['username']      = $params['username'];
            $rules = [
                        'username'         => 'required',
                        'password'      => 'required'
                    ];
            
            $validator = Validator::make($params, $rules);
            if ($validator->fails()) {
                throw new \Exception(implode(' ', $validator->errors()->all()));
            }

           $users = Loginusers::where("username",$params['username'])->get();     
            if(count($users) > 0){
                $validate = false;
                $userinfo = [];
                foreach ($users as $key => $value) {
                    $decrypted = Crypt::decryptString($value->password);
                    if($decrypted == $params['password']){
                        $validate = true;
                        $userinfo = $value;
                        if($userinfo->typeuser == "toko"){
                            $detail = Shop::where('id', $userinfo->userid)->first();
                            $userinfo->namatoko = $detail->namatoko;
                            $userinfo->alamat = $detail->alamat;
                            $userinfo->propinsi = $detail->propinsi;
                            $userinfo->kota = $detail->kota;
                            $userinfo->kecamatan = $detail->kecamatan;
                            $userinfo->telp = $detail->telp;
                            $userinfo->tipebisnis = $detail->tipebisnis;
                        }elseif ($userinfo->typeuser == "collector") {
                            $detail = User::where('id', $userinfo->userid)->first();
                            $userinfo->nama = $detail->namakaryawan;
                            $userinfo->recordownerid = $detail->recordownerid;
                        } elseif ($userinfo->typeuser == "sales") { 
			                $detail = User::where('id', $userinfo->userid)->first();
                            $userinfo->nama = $detail->namakaryawan;
                            $userinfo->recordownerid = $detail->recordownerid;
                    }
	              }
                }

                if($validate == false){
                    return response()->json([
                        'status'   =>'fail',
                        'message'  =>'wrong password',
                        'data' => [
                            'userinfo' => []
                        ]
                    ]);
                }else{
                    return response()->json([
                        'status'   =>'success',
                        'message'  =>'user found',
                        'data' => [
                            'userinfo' => $userinfo
                        ]
                    ]);
                }
            }else{
                $user2 = User2::where("username",$params['username'])->get();
                $validate = false;
                $userinfo = [];
                foreach ($user2 as $key => $value) {
                    $d = Hash::check($params['password'], $value->password);
                    if($d){
                        $validate = true;
                        $userinfo = $value;
                        $userinfo->typeuser = "collector";
                        $userinfo->api_token_sf = $userinfo->api_token;
                        unset($userinfo->password);
                        $cari = User::where('kodesales', $userinfo->userid);
                        if($cari->count() > 0) {
                            $hasil = $cari->first();
                            $userinfo->userid = $hasil->id;
                            $userinfo->recordownerid = $hasil->recordownerid;
                            $userinfo->typeuser = "sales";
                        } else {
                            $cari = User::where('kodecollector', $userinfo->userid);
                            if($cari->count() > 0) {
                                $hasil = $cari->first();
                                $userinfo->userid = $hasil->id;
                                $userinfo->recordownerid = $hasil->recordownerid;
                                $userinfo->typeuser = "collector";
                            } else {
                                $validate =false;
                            }
                        }
	              }
                }

                if($validate == false){
                    return response()->json([
                        'status'   =>'fail',
                        'message'  =>'wrong password',
                        'data' => [
                            'userinfo' => []
                        ]
                    ]);
                }else{
                    return response()->json([
                        'status'   =>'success',
                        'message'  =>'user found',
                        'data' => [
                            'userinfo' => $userinfo
                        ]
                    ]);
                }

                return response()->json([
                    'status'   =>'fail',
                    'message'  =>'username or email not found',
                    'data' => [
                        'userinfo'        => []
                    ]
                ]);
            }
    }

    public function detailToko(Request $request){
        $type = $request->userinfo->typeuser;
        if($type != "toko"){
            return response()->json([
                    'status'   =>'fail',
                    'message'  =>"type mismatch",
                    'data' => [
                        'detail' => []
                    ]
                ]);
        }else{
            $shop = Shop::where('id',$request->userinfo->userid)->first();
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'user found',
                        'data' => [
                            'detail' => $shop
                        ]
                    ]);
        }
    }

    public function updatePassword(Request $request){
            $params = $request->all();
            $params['passwordBaru']         = $params['password_baru'];
            $params['passwordLama']         = $params['password_lama'];
            $rules = [
                        'passwordBaru'      => 'required',
                        'passwordLama'      => 'required'
                    ];
            
            $validator = Validator::make($params, $rules);
            if ($validator->fails()) {
                throw new \Exception(implode(' ', $validator->errors()->all()));
            }

            $decrypted = Crypt::decryptString($request->userinfo->password);
            if($decrypted != $params['passwordLama']){
                return response()->json([
                        'status'   =>'fail',
                        'message'  =>"Old Password doesn't match"
                    ]);
            }else{
                $encrypted = Crypt::encryptString($params['passwordBaru']);
                $users = Loginusers::where("id",$request->userinfo->id)
                        ->update(["password"=>$encrypted]);
                $userinfo = Loginusers::where('id',$request->userinfo->id)->first();
                return response()->json([
                        'status'   =>'success',
                        'message'  =>'password has been updated',
                        'data' => [
                            'userinfo' => $userinfo
                        ]
                    ]);
            }
    }

     public function updateCollectorPassword(Request $request){
            $params = $request->all();
            $params['passwordBaru']         = $params['password_baru'];
            $params['passwordLama']         = $params['password_lama'];
            $rules = [
                        'passwordBaru'      => 'required',
                        'passwordLama'      => 'required'
                    ];
            
            $validator = Validator::make($params, $rules);
            if ($validator->fails()) {
                throw new \Exception(implode(' ', $validator->errors()->all()));
            }

            $cek = Hash::check($params['passwordLama'], $request->userinfo->password);
            if(!$cek){
                return response()->json([
                        'status'   =>'fail',
                        'message'  =>"Old Password doesn't match"
                    ]);
            }else{
                $encrypted = Hash::make($params['passwordBaru']);
                $users = User2::where("id",$request->userinfo->id)
                        ->update(["password"=>$encrypted]);
                $userinfo = User2::where('id',$request->userinfo->id)->first();
                return response()->json([
                        'status'   =>'success',
                        'message'  =>'password has been updated',
                        'data' => [
                            'userinfo' => $userinfo
                        ]
                    ]);
            }
    }

}
