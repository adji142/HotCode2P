<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Config;
use Validator;
use App\Model\Setoruang;
use App\Model\Tokobayar;
use App\Model\Tokobayardetail;
use App\Model\Kartupiutang;
use App\Model\Kartupiutangdetail;
use App\Model\Idenpembayaran;
use App\Model\Idenpembayarantokobayardetail;
use App\Model\Register;
use App\Model\Registerdetail;
use App\Model\Hasiltagihan;
use Illuminate\Support\Facades\Crypt;
class SetoruangController extends Controller{
    public function lists(Request $request){
        $collectorId = $request->userinfo->userid;
        $Setoruang = Setoruang::list($collectorId);
        if(count($Setoruang) > 0){
            foreach ($Setoruang as $key => $value) {
                $Setoruang[$key]->nominaltransfer = floatval($value->nominaltransfer);
            }

            return response()->json([
                        'status'   =>'success',
                        'message'  =>'data found',
                        'data' => [
                            'setoruang' => $Setoruang
                        ]
                    ]);
        }else{
            return response()->json([
                        'status'   =>'fail',
                        'message'  =>'no data found',
                        'data' => [
                            'setoruang' => []
                        ]
                    ]);
        }
    }

    public function transferlists(Request $request){
        $collectorId = $request->userinfo->userid;
        $Setoruang = Setoruang::listtransfer($collectorId);
        if(count($Setoruang) > 0){
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'data found',
                        'data' => [
                            'setoruang' => $Setoruang
                        ]
                    ]);
        }else{
            return response()->json([
                        'status'   =>'fail',
                        'message'  =>'no data found',
                        'data' => [
                            'setoruang' => []
                        ]
                    ]);
        }
    }

    public function create(Request $request){
        $collectorId = $request->userinfo->userid;
        $params = $request->all();
        $params['nominal']         = $params['nominal'];
        $params['berita']      = $params['berita'];
        $params['namabank']      = $params['nama_bank'];
        $params['tanggal']      = $params['tanggal'];
        $rules = [
                    'nominal'         => 'required',
                    'berita'      => 'required',
                    'namabank'      => 'required',
                    'tanggal'      => 'required'
                ];
        
        
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            throw new \Exception(implode(' ', $validator->errors()->all()));
        }


        $cekJumlahtotal = Tokobayardetail::cekAmountUnpaid($collectorId);
        if($params['nominal'] <= $cekJumlahtotal){
            $param = ['collectorid' => $collectorId, 
                                        'tgltransfer' => $params['tanggal'],
                                        'nominaltransfer' => $params['nominal'],
                                        'beritatransfer' => $params['berita'],
                                        'namabank' => $params['namabank'],
                                        "created_at"=>date('Y-m-d H:i:s'),
                                        "updated_at"=>date('Y-m-d H:i:s'),
                                    ];
            $insert = Setoruang::insert($param);
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'data found',
                        'data' => [
                            'setoruang' => $param
                        ]
                    ]);
        }else{
            return response()->json([
                        'status'   =>'fail',
                        'message'  =>'your nominal exceeded limit',
                        'data' => [
                            'setoruang' => []
                        ]
                    ]);
        }
    }

    public function sync(Request $request){
        $collectorId = $request->userinfo->userid;
        $setoruangData = [];
        $bayar = [];
        $assignTagihan = [];
        $hasilPembayaran = [];
        $identodetailbyr = [];
        if($request->input('setoruang')){
            if(gettype($request->input('setoruang')) == 'string') {
                $dtd = json_decode($request->input('setoruang'), true);
                $dtd = $dtd['setoruang'];
            }else{
                $dtd = $request->input('setoruang');
            }
            
            if(count($dtd) > 0){
                foreach ($dtd as $key => $value) {
                        $params = $value;
                        // dd($params);
                        $params['nominal']         = $params['nominaltransfer'];
                        $params['berita']      = $params['beritatransfer'];
                        $params['namabank']      = $params['namabank'];
                        $params['tanggal']      = $params['tgltransfer'];
                        $rules = [
                                    'nominal'         => 'required',
                                    'berita'      => 'required',
                                    'namabank'      => 'required',
                                    'tanggal'      => 'required'
                                ];
                        
                        
                        $validator = Validator::make($params, $rules);
                        if ($validator->fails()) {
                            throw new \Exception(implode(' ', $validator->errors()->all()));
                        }

                        if(isset($params['id'])){
                            $cekJumlahtotal = Tokobayardetail::cekAmountUnpaidUpdate($collectorId,$params['id']);
                            if($params['nominal'] <= $cekJumlahtotal){
                                $param = ['collectorid' => $collectorId, 
                                            'tgltransfer' => date('Y-m-d', strtotime($params['tanggal'])),
                                            'nominaltransfer' => $params['nominal'],
                                            'beritatransfer' => $params['berita'],
                                            'namabank' => $params['namabank'],
                                            "updated_at"=>date('Y-m-d H:i:s', strtotime($params['updated_at'])),
                                            "lastupdatedby" =>$request->userinfo->username
                                        ];
                                if(isset($params["bankisarowid"])){
                                    $param["bankisarowid"] = $params["bankisarowid"];
                                }
                                if(isset($params["idrekening"])){
                                    $param["idrekening"] = $params["idrekening"];
                                }
                                if(isset($params["nominaltambahan"])){
                                    $param["nominaltambahan"] = $params["nominaltambahan"];
                                }

                                if(isset($params["buktitransfer"])){
                                    $param["buktitransfer"] = $params["buktitransfer"];
                                }
                                $insert = Setoruang::where('id',$params['id'])->update($param);
                            }else{
                                return response()->json([
                                            'status'   =>'fail',
                                            'message'  =>'your nominal exceeded limit',
                                            'data' => [
                                                'setoruang' => []
                                            ]
                                        ]);
                            }
                        }else{
                            $cekJumlahtotal = Tokobayardetail::cekAmountUnpaid($collectorId);
                            if($params['nominal'] <= $cekJumlahtotal){
                                $ins = new Setoruang;
                                $ins->createdby = $request->userinfo->username;
                                $ins->collectorid = $collectorId;
                                $ins->tgltransfer = date('Y-m-d', strtotime($params['tanggal']));
                                $ins->nominaltransfer = $params['nominal'];
                                $ins->beritatransfer = $params['berita'];
                                $ins->namabank = $params['namabank'];
                                if(isset($params["bankisarowid"])){
                                    $ins->bankisarowid = $params["bankisarowid"];
                                }
                                if(isset($params["buktitransfer"])){
                                    $ins->buktitransfer = $params["buktitransfer"];
                                }
                                if(isset($params["idrekening"])){
                                    $ins->idrekening = $params["idrekening"];
                                }
                                if(isset($params["nominaltambahan"])){
                                    $ins->nominaltambahan = $params["nominaltambahan"];
                                }

                                $ins->created_at = date('Y-m-d H:i:s', strtotime($params['created_at']));
                                $ins->updated_at = date('Y-m-d H:i:s', strtotime($params['updated_at']));
                                $ins->save();
                                $dtd[$key]['idsetor'] = $ins->id;
                            }else{
                                return response()->json([
                                            'status'   =>'fail',
                                            'message'  =>'your nominal exceeded limit',
                                            'data' => [
                                                'setoruang' => []
                                            ]
                                        ]);
                            }
                        }
                }

                $setoruangData =  $dtd; // $request->input('setoruang');
                // dd($setoruangData);
            }
        }
        if($request->input('bayar')){
            if(gettype($request->input('bayar')) != 'array'){
                $decode = json_decode($request->input('bayar'));    
            }else{
                $decode['bayar'] = $request->input('bayar');
                $decode = json_encode($decode);
                $decode = json_decode($decode);
            }
            if(count($decode->bayar) > 0){
                $tokoIdTampung = [];
                $tokoIdBayarId = [];
                foreach ($decode->bayar as $key => $value) {
                    $value = json_decode(json_encode($value), true);
                    if($key == 0){
                        if ($value['tokobayarid'] > 0) {
                            $tokobayar = Tokobayar::where('id',$value['tokobayarid'])->first();
                            if($value['idbayar'] > 0) {
                                $tokobayardetail = Tokobayardetail::where('id', $value["idbayar"])->first();
                                $tokobayardetail->setorid = $value["setorid"];
                                $tokobayardetail->lastupdatedby = $request->userinfo->username;
                                $tokobayardetail->save();
                            } else {
                                if(isset($value["noreg"])) {
                                    $tokobayar->noreg = $value["noreg"];
                                }
                                $tokobayar->lastupdatedby = $request->userinfo->username;
                                $tokobayar->totalbayar = $tokobayar->totalbayar +  $value["nominaltransaksi"];
                                if($tokobayar->save()){
                                    $tokobayardetail = new Tokobayardetail;
                                    $tokobayardetail->tokobayarid = $value["tokobayarid"];
                                    $tokobayardetail->createdby = $request->userinfo->username;
                                    if(isset($value['tgltransaksi'])){
                                        $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value['tgltransaksi']));
                                    }else{
                                        $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value["created_at"]));
                                    }
                                    $tokobayardetail->created_at = date('Y-m-d H:i:s', strtotime($value["created_at"]));
                                    $tokobayardetail->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));
                                    if(isset($value['setorid'])){
                                        $tokobayardetail->setorid = $value["setorid"];    
                                    }
                                    $tokobayardetail->tipetransaksi = $value["tipetransaksi"];
                                    $tokobayardetail->nominaltransaksi = $value["nominaltransaksi"];

                                    if($value["tipetransaksi"] == "BGC"){
                                        $tokobayardetail->nobgc = $value["nobgc"];
                                        $tokobayardetail->jenisbgc = $value["jenisbgc"];     
                                        $tokobayardetail->tgljtbgc = date('Y-m-d', strtotime($value["tgljtbgc"]));
                                        if(isset($value['attachment'])) {
                                            $tokobayardetail->attachment = $value["attachment"];
                                        }
                                    }
                                    if(isset($value["bank"])){
                                        $tokobayardetail->bank = $value["bank"];
                                    }

                                    $tokobayardetail->save();
                                }
                            }

                            if(isset($value["baseObjId"])){
                                $tokobayardetail->baseObjId = $value["baseObjId"];
                            }

                            $tokobayar->tokobayarid = $tokobayar->id;
                            $new = json_decode(json_encode($tokobayar));
                            $new->detail= [json_decode(json_encode($tokobayardetail))];
                            $bayar[$value["tokoid"]] = $new; 
                        } else {
                            $tokobayar = new Tokobayar;
                            $tokobayar->createdby = $request->userinfo->username;
                            $tokobayar->collectorid = $collectorId;
                            $tokobayar->tokoid = $value["tokoid"];

                            if(isset($value['isarowid'])){
                                $tokobayar->isarowid = $value['isarowid'];
                            }
                            if(isset($value["noreg"])) {
                                $tokobayar->noreg = $value["noreg"];
                            }

                            if(isset($value['registerid'])){
                                $tokobayar->registerid = $value['registerid'];
                            }

                            if(isset($value['tgltransaksi'])){
                                $tokobayar->tglbayar = date('Y-m-d', strtotime($value['tgltransaksi']));
                            }else{
                                $tokobayar->tglbayar = date('Y-m-d', strtotime($value["created_at"]));    
                            }

                            $tokobayar->created_at = date('Y-m-d H:i:s', strtotime($value["created_at"]));
                            $tokobayar->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));    
                            
                            $tokobayar->totalbayar = $value["nominaltransaksi"];
                            if(isset($value["keterangan"])){
                                $tokobayar->keterangan = $value["keterangan"];    
                            }
                            
                            if(isset($value["foto"])){
                                if($value["foto"] != "" and $value["foto"] != null){
                                    $tokobayar->foto = $value["foto"];
                                }
                            }

                            if(isset($value["ttd"])){
                                if($value["ttd"] != "" and $value["ttd"] != null){
                                    $tokobayar->ttd = $value["ttd"];
                                }
                            }

                            if($tokobayar->save()){
                                $tokoIdTampung[] = $value["tokoid"];
                                $tokoIdBayarId[$value["tokoid"]] = $tokobayar->id;

                                $tokobayardetail = new Tokobayardetail;
                                $tokobayardetail->tokobayarid = $tokobayar->id;
                                $tokobayardetail->createdby = $request->userinfo->username;
                                $tokobayardetail->created_at = date('Y-m-d H:i:s', strtotime($value["created_at"]));
                                $tokobayardetail->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));
                                if(isset($value['tgltransaksi'])){
                                    $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value['tgltransaksi']));
                                }else{
                                    $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value["created_at"]));
                                }
                                
                                if(isset($value['setorid'])){
                                    $tokobayardetail->setorid = $value["setorid"];    
                                }
                                $tokobayardetail->tipetransaksi = $value["tipetransaksi"];
                                $tokobayardetail->nominaltransaksi = $value["nominaltransaksi"];

                                if($value["tipetransaksi"] == "BGC"){
                                    $tokobayardetail->nobgc = $value["nobgc"];
                                    $tokobayardetail->jenisbgc = $value["jenisbgc"];     
                                    $tokobayardetail->tgljtbgc = date('Y-m-d', strtotime($value["tgljtbgc"]));
                                    if(isset($value['attachment'])) {
                                        $tokobayardetail->attachment = $value["attachment"];
                                    }
                                }

                                $tokobayardetail->save(); 
                                // dd($tokobayar);
                                if(isset($value["baseObjId"])){
                                    $tokobayardetail->baseObjId = $value["baseObjId"];
                                }

                                if(isset($value["bank"])){
                                    $tokobayardetail->bank = $value["bank"];
                                }

                                $tokobayar->tokobayarid = $tokobayar->id;
                                $new = json_decode(json_encode($tokobayar));
                                $new->detail= [json_decode(json_encode($tokobayardetail))];
                                $bayar[$value["tokoid"]] = $new; 
                            }                  
                        }
                    }else{
                        if(in_array($value["tokoid"], $tokoIdTampung)){
                            $tokobayar = Tokobayar::where('id',$tokoIdBayarId[$value["tokoid"]])->first();
                            $tokobayar->lastupdatedby = $request->userinfo->username;
                            $tokobayar->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));
                            if($value['idbayar'] > 0) {
                                $tokobayardetail = Tokobayardetail::where('id', $value["idbayar"])->first();
                                $tokobayardetail->lastupdatedby = $request->userinfo->username;
                                $tokobayardetail->setorid = $value["setorid"];
                                $tokobayardetail->save();
                            } else {
                                $tokobayardetail = new Tokobayardetail;
                                $tokobayardetail->created_at = date('Y-m-d H:i:s', strtotime($value["created_at"]));
                                $tokobayardetail->createdby = $request->userinfo->username;
                                $tokobayardetail->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));
                                $tokobayardetail->tokobayarid = $tokobayar->id;
                                if(isset($value['tgltransaksi'])){
                                    $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value['tgltransaksi']));
                                }else{
                                    $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value["created_at"]));
                                }
                                $tokobayardetail->tipetransaksi = $value["tipetransaksi"];
                                $tokobayardetail->nominaltransaksi = $value["nominaltransaksi"];
                                if(isset($value['setorid'])){
                                    $tokobayardetail->setorid = $value["setorid"];    
                                }

                                if($value["tipetransaksi"] == "BGC"){
                                    $tokobayardetail->nobgc = $value["nobgc"];
                                    $tokobayardetail->jenisbgc = $value["jenisbgc"];     
                                    $tokobayardetail->tgljtbgc = date('Y-m-d', strtotime($value["tgljtbgc"]));  
                                    if(isset($value['attachment'])) {
                                        $tokobayardetail->attachment = $value["attachment"];
                                    }                             
                                }

                                if(isset($value["bank"])){
                                    $tokobayardetail->bank = $value["bank"];
                                }

                                $tokobayardetail->save(); 
                                if(isset($value["noreg"])) {
                                    $tokobayar->noreg = $value["noreg"];
                                }
                                $tokobayar->totalbayar = $tokobayar->totalbayar +  $value["nominaltransaksi"];
                                $tokobayar->save();
                            }
                            
                            $new = $bayar[$value["tokoid"]];
                            if(isset($value["baseObjId"])){
                                $tokobayardetail->baseObjId = $value["baseObjId"];
                            }
                            $new->detail[] = $tokobayardetail;
                            
                            $bayar[$value["tokoid"]] = $new; 
                        }else{
                            if ($value['tokobayarid'] > 0) {
                                $tokobayar = Tokobayar::where('id',$value['tokobayarid'])->first();
                                $tokobayar->lastupdatedby = $request->userinfo->username;
                                if($value['idbayar'] > 0) {
                                    $tokobayardetail = Tokobayardetail::where('id', $value["idbayar"])->first();
                                    $tokobayardetail->lastupdatedby = $request->userinfo->username;
                                    $tokobayardetail->setorid = $value["setorid"];
                                    $tokobayardetail->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));
                                    $tokobayardetail->save();
                                } else {
                                    if(isset($value["noreg"])) {
                                        $tokobayar->noreg = $value["noreg"];
                                    }
                                    $tokobayar->totalbayar = $tokobayar->totalbayar +  $value["nominaltransaksi"];
                                    $tokobayar->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));
                                    if($tokobayar->save()){
                                        $tokobayardetail = new Tokobayardetail;
                                        $tokobayardetail->createdby = $request->userinfo->username;
                                        $tokobayardetail->tokobayarid = $value["tokobayarid"];
                                        if(isset($value['tgltransaksi'])){
                                            $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value['tgltransaksi']));
                                        }else{
                                            $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value["created_at"]));
                                        }
                                        $tokobayardetail->created_at = date('Y-m-d H:i:s', strtotime($value["created_at"]));
                                        $tokobayardetail->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));
                                        if(isset($value['setorid'])){
                                            $tokobayardetail->setorid = $value["setorid"];    
                                        }
                                        $tokobayardetail->tipetransaksi = $value["tipetransaksi"];
                                        $tokobayardetail->nominaltransaksi = $value["nominaltransaksi"];

                                        if($value["tipetransaksi"] == "BGC"){
                                            $tokobayardetail->nobgc = $value["nobgc"];
                                            $tokobayardetail->jenisbgc = $value["jenisbgc"];     
                                            $tokobayardetail->tgljtbgc = date('Y-m-d', strtotime($value["tgljtbgc"]));
                                            if(isset($value['attachment'])) {
                                                $tokobayardetail->attachment = $value["attachment"];
                                            }
                                        }

                                        if(isset($value["bank"])){
                                            $tokobayardetail->bank = $value["bank"];
                                        }

                                        $tokobayardetail->save();
                                    }
                                }

                                if(isset($value["baseObjId"])){
                                    $tokobayardetail->baseObjId = $value["baseObjId"];
                                }

                                $tokobayar->tokobayarid = $tokobayar->id;
                                $new = json_decode(json_encode($tokobayar));
                                $new->detail= [json_decode(json_encode($tokobayardetail))];
                                $bayar[$value["tokoid"]] = $new; 
                            } else {
                                $tokobayar = new Tokobayar;
                                $tokobayar->createdby = $request->userinfo->username;
                                $tokobayar->collectorid = $collectorId;
                                $tokobayar->tokoid = $value["tokoid"];
                                $tokobayar->created_at = date('Y-m-d H:i:s', strtotime($value["created_at"]));
                                $tokobayar->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));

                                if(isset($value['isarowid'])){
                                    $tokobayar->isarowid = $value['isarowid'];
                                }
                                if(isset($value["noreg"])) {
                                    $tokobayar->noreg = $value["noreg"];
                                }

                                if(isset($value['registerid'])){
                                    $tokobayar->registerid = $value['registerid'];
                                }

                                if(isset($value['tgltransaksi'])){
                                    $tokobayar->tglbayar = date('Y-m-d', strtotime($value['tgltransaksi']));
                                }else{
                                    $tokobayar->tglbayar = date('Y-m-d', strtotime($value["created_at"]));    
                                }
                                
                                $tokobayar->totalbayar = $value["nominaltransaksi"];
                                if(isset($value["keterangan"])){
                                    $tokobayar->keterangan = $value["keterangan"];    
                                }
                                
                                if(isset($value["foto"])){
                                    if($value["foto"] != "" and $value["foto"] != null){
                                        $tokobayar->foto = $value["foto"];
                                    }
                                }

                                if(isset($value["ttd"])){
                                    if($value["ttd"] != "" and $value["ttd"] != null){
                                        $tokobayar->ttd = $value["ttd"];
                                    }
                                }

                                if($tokobayar->save()){
                                    $tokoIdTampung[] = $value["tokoid"];
                                    $tokoIdBayarId[$value["tokoid"]] = $tokobayar->id;

                                    $tokobayardetail = new Tokobayardetail;
                                    $tokobayardetail->tokobayarid = $tokobayar->id;
                                    $tokobayardetail->createdby = $request->userinfo->username;
                                    $tokobayardetail->created_at = date('Y-m-d H:i:s', strtotime($value["created_at"]));
                                    $tokobayardetail->updated_at = date('Y-m-d H:i:s', strtotime($value["updated_at"]));
                                    if(isset($value['tgltransaksi'])){
                                        $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value['tgltransaksi']));
                                    }else{
                                        $tokobayardetail->tgltransaksi = date('Y-m-d', strtotime($value["created_at"]));
                                    }
                                    
                                    if(isset($value['setorid'])){
                                        $tokobayardetail->setorid = $value["setorid"];    
                                    }
                                    $tokobayardetail->tipetransaksi = $value["tipetransaksi"];
                                    $tokobayardetail->nominaltransaksi = $value["nominaltransaksi"];

                                    if($value["tipetransaksi"] == "BGC"){
                                        $tokobayardetail->nobgc = $value["nobgc"];
                                        $tokobayardetail->jenisbgc = $value["jenisbgc"];     
                                        $tokobayardetail->tgljtbgc = date('Y-m-d', strtotime($value["tgljtbgc"]));
                                        if(isset($value['attachment'])) {
                                            $tokobayardetail->attachment = $value["attachment"];
                                        }
                                    }

                                    if(isset($value["bank"])){
                                        $tokobayardetail->bank = $value["bank"];
                                    }

                                    $tokobayardetail->save(); 
                                    // dd($tokobayar);
                                    if(isset($value["baseObjId"])){
                                        $tokobayardetail->baseObjId = $value["baseObjId"];
                                    }
                                    $tokobayar->tokobayarid = $tokobayar->id;
                                    $new = json_decode(json_encode($tokobayar));
                                    $new->detail= [json_decode(json_encode($tokobayardetail))];
                                    $bayar[$value["tokoid"]] = $new; 
                                }                  
                            }
                        }
                    }                    
                }
            }
        }

        if($request->input('hasiltagihan')){
            if(count($request->input('hasiltagihan')) > 0){
                foreach ($request->input('hasiltagihan') as $key => $value) {
                    $insert = new Hasiltagihan;
                    $insert->createdby = $request->userinfo->username;
                    $insert->created_at = date('Y-m-d H:i:s', strtotime($value['created_at']));
                    $insert->updated_at = date('Y-m-d H:i:s', strtotime($value['updated_at']));
                    $insert->tokoid = $value["idtoko"];
                    $insert->tgltagih = $value["tgl_tagih"];
                    if(isset($value["noreg"])) {
                        $insert->noreg = $value["noreg"];
                    }                    
                    $insert->keterangantagih = $value["keterangan"];
                    $insert->registerid = $value["registerid"];

                    if(isset($value['foto'])){
                        $insert->foto = $value['foto'];
                    }
                    if(isset($value['ttd'])){
                        $insert->ttd = $value['ttd'];
                    }
                    $insert->save();

                    if(isset($value["baseObjId"])){
                        $insert->baseObjId = $value["baseObjId"];
                    }

                    $hasilPembayaran[] = $insert;
                }
            }
        }

        if($request->input('assignTagihan')){
            if(gettype($request->input('assignTagihan')) != 'array'){
                $decode = json_decode($request->input('assignTagihan'));    
            }else{
                $decode['assignTagihan'] = $request->input('assignTagihan');
                $decode = json_encode($decode);
                $decode = json_decode($decode);
            }
            
            foreach ($decode->assignTagihan as $key => $value) {
                $value = json_decode(json_encode($value), true);
               $select = Register::select('ptg.tagihan.*', DB::raw('sum(ptg.tagihandetail.rptagih) as jumlahTagih'))
                                    ->join('ptg.tagihandetail', 'ptg.tagihandetail.tagihanid', '=', 'ptg.tagihan.id')
                                    ->join('ptg.kartupiutang', 'ptg.tagihandetail.kartupiutangid', '=', 'ptg.kartupiutang.id')
                                    ->where('ptg.tagihan.id', $value["registerid"])
                                    ->where('ptg.kartupiutang.id', $value["kartupiutangid"])
                                    ->groupBy('ptg.tagihan.id')
                                    ->get();
                if(count($select) > 0 || $value["kartupiutangid"] == -1){
                    if ($value["kartupiutangid"] == -1) {
                        $insert = new Idenpembayaran;
                        $insert->createdby = $request->userinfo->username;
                        $insert->registerid = $value["registerid"];
                        $insert->kartupiutangid = $value["kartupiutangid"];
                        $insert->created_at = date('Y-m-d H:i:s', strtotime($value['created_at']));
                        $insert->updated_at = date('Y-m-d H:i:s', strtotime($value['updated_at']));
                        $insert->nominaltagih = $value['nominaltagih'];
                        $insert->nominaliden = $value["nominaliden"]; 
                        if(isset($value["noreg"])){
                            $insert->noreg = $value["noreg"];
                        }
                        if(isset($value["keterangantagih"])){
                            $insert->keterangantagih = $value["keterangantagih"];
                        }
                        if(isset($value["tokoid"])){
                            $insert->tokoid = $value["tokoid"];
                        }
                        $insert->save();

                        if(isset($value["baseObjId"])){
                            $insert->baseObjId = $value["baseObjId"];
                        }
                        $assignTagihan[] = $insert;
                    } else {
                        foreach ($select as $kei => $val) {
                            $validate = Idenpembayaran::select(DB::raw('sum(nominaliden) as jumlah'))
                                    ->where('kartupiutangid', $value["kartupiutangid"])
                                    ->where('registerid', $value["registerid"])
                                    ->groupBy('registerid')
                                    ->first();
                                    
                            if(count($validate) > 0){
                                $jumlah = (float)$validate->jumlah;    
                            }else{
                                $jumlah = 0;
                            }
                            
                            if($jumlah >= $val['jumlahtagih']){
                                return response()->json([
                                        'status'   =>'fail',
                                        'message'  =>'piutang sudah lunas',
                                        'data' => []
                                    ]);
                            }else{
                                if($value["nominaliden"] > ($val['jumlahtagih']- $jumlah)){
                                    return response()->json([
                                        'status'   =>'fail',
                                        'message'  =>'nominaliden melebihi jumlah pembayaran',
                                        'data' => []
                                    ]);
                                }else{
                                    $insert = new Idenpembayaran;
                                    $insert->registerid = $value["registerid"];
                                    $insert->createdby = $request->userinfo->username;
                                    $insert->kartupiutangid = $value["kartupiutangid"];
                                    $insert->created_at = date('Y-m-d H:i:s', strtotime($value['created_at']));
                                    $insert->updated_at = date('Y-m-d H:i:s', strtotime($value['updated_at']));
                                    $insert->nominaltagih = $val['jumlahtagih'] - $jumlah;
                                    $insert->nominaliden = $value["nominaliden"]; 
                                    if(isset($value["noreg"])){
                                        $insert->noreg = $value["noreg"];
                                    }
                                    if(isset($value["tokoid"])){
                                        $insert->tokoid = $value["tokoid"];
                                    }
                                    if(isset($value["keterangantagih"])){
                                        $insert->keterangantagih = $value["keterangantagih"];
                                    }
                                    $insert->save();

                                    if(isset($value["baseObjId"])){
                                        $insert->baseObjId = $value["baseObjId"];
                                    }
                                    $assignTagihan[] = $insert;
                                }  
                            }                        
                        }
                    }
                }else{
                    return response()->json([
                                    'status'   =>'fail',
                                    'message'  =>'id register atau kartupiutang tidak ditemukan',
                                    'data' => []
                                ]);
                }
            }
        }

        if($request->input('identodetailbyr')){
            if(gettype($request->input('identodetailbyr')) == 'string') {
                $dtd = json_decode($request->input('identodetailbyr'), true);
                $dtd = $dtd['identodetailbyr'];
            }else{
                $dtd = $request->input('identodetailbyr');
            }
            foreach ($dtd as $key => $value) {
            //     dd($value['created_at']);
            //    dd(date('Y-m-d H:i:s', strtotime($value['created_at'])));
               $createdat = date('Y-m-d H:i:s', strtotime($value['created_at']));
               $cek = Idenpembayarantokobayardetail::where('idenpembayaranid', $value['idenpembayaranid'])
                     ->where('tokobayardetailid', $value['tokobayardetailid'])
                     ->where('nominaliden', $value['nominaliden'])
                     ->where('created_at', $createdat);
                // dd($cek->count());
                if ($cek->count() > 0) {
                    $value['id'] = $cek->first()->id;
                    $identodetailbyr[] = $value;
                    continue;
                } else {
                    $new = new Idenpembayarantokobayardetail;
                    $new->idenpembayaranid = $value["idenpembayaranid"];
                    $new->tokobayardetailid = $value['tokobayardetailid'];
                    $new->nominaliden = $value['nominaliden'];
                    $new->created_at = date('Y-m-d H:i:s', strtotime($value['created_at']));
                    $new->updated_at = date('Y-m-d H:i:s', strtotime($value['updated_at']));
                    $new->createdby = $request->userinfo->username;
                    $new->lastupdatedby = $request->userinfo->username;
                    $new->save();
                    $value['id'] = $new->id;
                    $value['createdby'] = $new->createdby;
                    $value['lastupdatedby'] = $new->lastupdatedby;
                    $identodetailbyr[] = $value;
                }
            }
        }

        return response()->json([
                                    'status'   =>'success',
                                    'message'  =>'sync success',
                                    'data' => [
                                        'setoruang' =>$setoruangData,
                                        'bayar' =>array_values($bayar),
                                        'hasilTagihan' =>$hasilPembayaran,
                                        'assignTagihan' => $assignTagihan,
                                        'identodetailbyr' => $identodetailbyr
                                    ]
                                ]);
    }
}