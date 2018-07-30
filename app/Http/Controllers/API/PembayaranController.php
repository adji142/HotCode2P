<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Config;
use Validator;
use App\Model\User;
use App\Model\Loginusers;
use App\Model\Register;
use App\Model\Kartupiutang;
use App\Model\Registerdetail;
use App\Model\Tokobayar;
use App\Model\Tokobayardetail;
use App\Model\Idenpembayaran;
use App\Model\Idenpembayarantokobayardetail;
use App\Model\Setoruang;
use App\Model\Hasiltagihan;
use Illuminate\Support\Facades\Crypt;
class PembayaranController extends Controller{
    public function lists(Request $request){
        $collectorId = $request->userinfo->userid;
        $bayar = Tokobayardetail::list($collectorId);
        $setor = Setoruang::list($collectorId);
        $kas = $transfer = $disetor = 0;
        $giro = [];
        $giroTotal = 0;
        $trans = [];
        if(count($bayar) > 0){
            foreach ($bayar as $key => $value) {
                if($value->tipetransaksi == "KAS"){
                    $kas += $value->nominaltransaksi;
                }elseif($value->tipetransaksi == "BGC"){
                    $k = count($giro);
                    $giroTotal += $value->nominaltransaksi;
                    $giro[$k]["nominal"] = floatval($value->nominaltransaksi);
                    $giro[$k]["nomorGiro"] = $value->nobgc;
                }else{
                    $value->nominaltransaksi = floatval($value->nominaltransaksi);
                    $trans[]  = $value;
                    $transfer += $value->nominaltransaksi;
                }
            }
        }

        if(count($bayar) > 0){
            foreach ($setor as $key => $value) {
                $disetor += $value->nominaltransfer;
            }
        }

        return response()->json([
                        'status'   =>'success',
                        'message'  =>'data status',
                        'data' => [
                            "tunai"=>["total"=> $kas, 
                                    "isi"=>['tunaiDiTangan' =>  $kas,
                                            'tunaiDiTransfer'=>$disetor,
                                            'tunaiDiSetor'=> $kas - $disetor]
                                    ],
                            'transfer'=>["total"=>$transfer, "isi"=> $trans],
                            'giro'=>["total"=>$giroTotal, "isi"=>$giro]
                        ]
                    ]);
    }

    public function pullIdenpembayaran(Request $request) {
        $collectorId = $request->userinfo->userid;
        $list = Idenpembayaran::daftar($collectorId);
        if(count($list) > 0) {
            $status = 'data found';
        } else {
            $status = 'data not found';
        }
        return response()->json([
            'status'   =>'success',
            'message'  =>$status,
            'data' => $list
        ]);
    }

    public function pullHasiltagihan(Request $request) {
        $collectorId = $request->userinfo->username;
        $list = Hasiltagihan::lists($collectorId);
        if(count($list) > 0) {
            $status = 'data found';
        } else {
            $status = 'data not found';
        }
        return response()->json([
            'status'   =>'success',
            'message'  =>$status,
            'data' => $list
        ]);
    }

    public function datalists(Request $request){
        $collectorId = $request->userinfo->userid;
        $bayar = Tokobayardetail::list($collectorId);
        $setor = Setoruang::list($collectorId);
        $disetor = [];
        $dibayar = [];
        if(count($bayar) > 0){
            foreach ($bayar as $key => $value) {
                $dibayar[] = $value;
            }
        }

        if(count($bayar) > 0){
            foreach ($setor as $key => $value) {
                $disetor[] = $value;
            }
        }

        return response()->json([
                        'status'   =>'success',
                        'message'  =>'data status',
                        'data' => [
                            'bayar'=>$dibayar
                        ]
                    ]);
    }
}
