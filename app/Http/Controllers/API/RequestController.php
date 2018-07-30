<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Config;
use Validator;
use App\Model\Requesttoko;
use App\Model\AppSetting;
use App\Model\BankKota;
use Illuminate\Support\Facades\Crypt;
class RequestController extends Controller{
    public function requesttoko(Request $request){
        $toko = $request->userinfo->userid;
        $req = Requesttoko::listByToko($toko);
        if(count($req) > 0){
            foreach ($req as $key => $value) {
                $req[$key]->tglrequest = date('d-m-Y', strtotime($value->tglrequest));
            }
        }

        return response()->json([
                        'status'   =>'success',
                        'message'  =>'data request',
                        'data' => ["request"=>$req]
                    ]);
    }

    public function create(Request $request){
        $toko = $request->userinfo->userid;
        $params = $request->all();
        $params['keterangan']         = $params['keterangan'];
        $rules = [
                    'keterangan'         => 'required',
                ];
        
        
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            throw new \Exception(implode(' ', $validator->errors()->all()));
        }
        $insert = new Requesttoko;
        $insert->tokoid = $toko;
        $insert->createdby = $request->userinfo->username;
        $insert->tglrequest = date('Y-m-d');
        $insert->createdon = date('Y-m-d H:i:s');
        $insert->keterangan = $params["keterangan"];
        if($insert->save()){
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'request has been saved',
                        'data' => $insert
                    ]);    
        }else{
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'failed to save',
                        'data' => []
                    ]);    
        }
        
    }

    public function urlSetting(Request $request){
       
        // $url = Appsetting::where('keyid', 'URL')->toSql();
        $url = DB::connection('pgsql2')->select("select * from history.appsetting where keyid = 'URL'");
        $a = [];
        if(count($url) > 0){
            foreach ($url as $key => $value) {
                $a[] = ["keyid"=>$value->keyid,
                        "keterangan"=>$value->keterangan,
                            "value"=>$value->value]; 
            }
        }

        if(count($url) > 0){
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'data found',
                        'data' => $a[0]
                    ]);    
        }else{
            return response()->json([
                        'status'   =>'fail',
                        'message'  =>'No data found',
                        'data' => []
                    ]);    
        }
        
    }

    public function bankDaftar(Request $request){
       
        // $url = Appsetting::where('keyid', 'URL')->toSql();
        $bank = BankKota::where('aktif', 't')->get();
        $a = [];
        if(count($bank) > 0){
            foreach ($bank as $key => $value) {
                $a[] = ["id"=>$value->id,
                        "namabankdankota"=>$value->namabankdankota
                        ]; 
            }
        }

        if(count($bank) > 0){
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'data found',
                        'data' => $a
                    ]);    
        }else{
            return response()->json([
                        'status'   =>'fail',
                        'message'  =>'No data found',
                        'data' => []
                    ]);    
        }
        
    }
}
