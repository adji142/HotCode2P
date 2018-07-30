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
use App\Model\Kartupiutangdetail;
use App\Model\Registerdetail;
use App\Model\Tokobayar;
use App\Model\Idenpembayaran;
use Illuminate\Support\Facades\Crypt;
class RegisterController extends Controller{
    public function lists(Request $request){
        $collectorId = $request->userinfo->userid;
        $registerList = Register::list($collectorId);
        if(count($registerList) > 0){
            foreach ($registerList as $key => $value) {
                $registerDetailList = Registerdetail::listKartupiutang($value->id);
                if(count($registerDetailList) > 0){
                    $toko = [];
                    $arrDataToko = [];
                    foreach ($registerDetailList as $ke => $v) {
                        // dd($v);
                        if(!in_array($v->kodetoko, $toko)){
                            $toko[] = $v->kodetoko;
                            $keo = array_search($v->kodetoko, $toko);
                            $bayar = Idenpembayaran::list($v->kartupiutangid);
                            $password = Loginusers::where("userid",$v->tokoid)->first();
                            $arrDataToko[$keo]["nama"] = $v->namatoko;
                            $arrDataToko[$keo]["wilayah"] = $value->wilayah;
                            if(count($password) > 0){
                                $arrDataToko[$keo]["password"] = base64_encode(Crypt::decryptString($password->password));
                            }else{
                                $arrDataToko[$keo]["password"] = "undefined";
                            }

                            $arrDataToko[$keo]["TokoIDWiser"] = $v->TokoIDWiser;
                            $arrDataToko[$keo]["tokoIDISA"] = $v->tokoIDISA;
                            $arrDataToko[$keo]["amountTotal"] = $v->rptagih;
                            $arrDataToko[$keo]["amountPaidTotal"] = 0;

                            if(count($bayar) > 0){
                                foreach ($bayar as $kk => $vv) {
                                    $arrDataToko[$keo]["amountPaidTotal"] += $vv->nominaliden;
                                    $v->bayar += $vv->nominaliden;
                                }
                            }else{ 
                                $v->bayar = 0;                            
                            }

                            $arrDataToko[$keo]["listnota"] = [
                                                                [
                                                                    "kartupiutangid"=> $v->kartupiutangid,
                                                                    "tagihanid"=> $v->tagihanid,
                                                                    "detailId"=> $v->detailId,
                                                                    "tglJthtempo"=> date('d-m-Y', strtotime($v->tgljt)),
                                                                    "tglNota"=> date('d-m-Y', strtotime($v->tglproforma)),
                                                                    "rptagih"=> $v->rptagih,
                                                                    "bayar"=> $v->bayar,
                                                                    "nonota"=> $v->nonota,
                                                                ]
                                                             ];

                        }else{
                            $kei = array_search($v->kodetoko, $toko);
                            $bayar = Idenpembayaran::list($v->kartupiutangid);
                            if(count($bayar) > 0){
                                foreach ($bayar as $kk => $vv) {
                                    $arrDataToko[$kei]["amountPaidTotal"] += $vv->nominaliden;
                                    $v->bayar += $vv->nominaliden;                                
                                }
                            }else{ 
                                $v->bayar = 0;                            
                            }

                            $arrDataToko[$kei]["listnota"][] = [
                                                                    "kartupiutangid"=> $v->kartupiutangid,
                                                                    "tagihanid"=> $v->tagihanid,
                                                                    "detailId"=> $v->detailId,
                                                                    "tglJthtempo"=> date('d-m-Y', strtotime($v->tgljt)),
                                                                    "tglNota"=> date('d-m-Y', strtotime($v->tglproforma)),
                                                                    "rptagih"=> $v->rptagih,
                                                                    "bayar"=> $v->bayar,
                                                                    "nonota"=> $v->nonota,
                                                                ];
                            $arrDataToko[$kei]["amountTotal"] += $v->rptagih;
                        }
                    }
                }

                if(isset($arrDataToko)){
                    $registerList[$key]->nota = $arrDataToko;
                    unset($registerList[$key]->wilayah);
                }
            }

            return response()->json([
                        'status'   =>'success',
                        'message'  =>'data found',
                        'data' => [
                            'register' => $registerList
                        ]
                    ]);
        }else{
            return response()->json([
                        'status'   =>'fail',
                        'message'  =>'no data found',
                        'data' => [
                            'register' => []
                        ]
                    ]);
        }
    }

    public function piutang(Request $request){
        $toko = $request->userinfo->userid;
        $piutang = Kartupiutang::select('ptg.kartupiutang.*', DB::raw('sum(ptg.kartupiutangdetail.nomtrans) as summarytransfer'))
                                ->leftjoin('ptg.kartupiutangdetail','ptg.kartupiutangdetail.kartupiutangid','=','ptg.kartupiutang.id')
                                ->where('ptg.kartupiutang.tokoid', $toko)
                                ->groupBy('ptg.kartupiutang.id')
                                ->get();
        // dd($piutang);                      
        // $host ="202.74.238.28";
        // $username ="devjavasign";
        // $password ="devjavasign";
        // $database ="devwiser";
         
        // $conn_string = "host=$host port=1103 dbname=$database user=$username password=$password";
        // $koneksi = pg_connect($conn_string);
        // dd($koneksi);
        $saldopiutangJthTempo= $saldopiutangBlmJthTempo = 0;
        $tanggalBayarAkhir='';
        $dataPiutang = [];
        $dataPiutangjath = [];
        $dataPiutangblmjath = [];
        $now = date('Y-m-d');
        $tigaBulan = date('Y-m-d', strtotime('-3 month', strtotime($now)));
        if(count($piutang) > 0){
            foreach ($piutang as $key => $value) {
                if($value->nomnota - $value->summarytransfer > 0){
                    $dataPiutang[] = ["idkartupiutang"=>$value->id,"nonota"=>$value->nonota,"nomnota"=>(double)$value->nomnota, "tgljt"=>date('d-m-Y', strtotime($value->tgljt)),"tglNota"=>date('d-m-Y', strtotime($value->tglproforma)),"saldoPtg"=>($value->nomnota - $value->summarytransfer)];

                    if($value->tgljt > $now){
                        $dataPiutangblmjath[] = ["idkartupiutang"=>$value->id,"nonota"=>$value->nonota,"nomnota"=>(double)$value->nomnota, "tgljt"=>date('d-m-Y', strtotime($value->tgljt)),"tglNota"=>date('d-m-Y', strtotime($value->tglproforma)),"saldoPtg"=>($value->nomnota - $value->summarytransfer)];
                        $saldopiutangBlmJthTempo += $value->nomnota - $value->summarytransfer;
                    }elseif($value->tgljt < $now){
                        $dataPiutangjath[] = ["idkartupiutang"=>$value->id,"nonota"=>$value->nonota,"nomnota"=>(double)$value->nomnota, "tgljt"=>date('d-m-Y', strtotime($value->tgljt)),"tglNota"=>date('d-m-Y', strtotime($value->tglproforma)),"saldoPtg"=>($value->nomnota - $value->summarytransfer)];
                        $saldopiutangJthTempo += $value->nomnota - $value->summarytransfer;
                    }

                }else if($value->tgljt <= $now and $value->tgljt >= $tigaBulan){
                    $dataPiutang[] = ["idkartupiutang"=>$value->id,"nonota"=>$value->nonota,"nomnota"=>(double)$value->nomnota, "tgljt"=>date('d-m-Y', strtotime($value->tgljt)),"tglNota"=>date('d-m-Y', strtotime($value->tglproforma)),"saldoPtg"=>($value->nomnota - $value->summarytransfer)];
                }

            }
        }

        $bayar = Kartupiutangdetail::select('ptg.kartupiutangdetail.*')
                                    ->join('ptg.kartupiutang','ptg.kartupiutangdetail.kartupiutangid','=','ptg.kartupiutang.id')
                                    ->where('ptg.kartupiutang.tokoid',$toko)->orderBy('tgltrans','DESC')->get();

        $dataBayar = [];
        if(count($bayar) > 0){
            $tanggalBayarAkhir= $bayar[0]->tgltrans;
            foreach ($bayar as $key => $value) {
                $dataBayar[] = ["kartupiutangid"=>$value->kartupiutangid,"tgltrans"=>$value->tgltrans,"nomtrans"=>(double)$value->nomtrans, "kodetrans"=>$value->kodetrans];
            }
        }

        return response()->json([
                        'status'   =>'success',
                        'message'  =>'data piutang',
                        'data' => [
                            'saldoTotalPiutang' => $saldopiutangBlmJthTempo + $saldopiutangJthTempo,
                            'jatuhTempo'=>$saldopiutangJthTempo,
                            'blmJatuhTempo'=>$saldopiutangBlmJthTempo,
                            'tanggalBayarTerakhir'=>date('d-m-Y', strtotime($tanggalBayarAkhir)),
                            'nota'=>["blmJatuhTempo"=>$dataPiutangblmjath, "jatuhtempo"=>$dataPiutangjath],
                            'bayar'=>$dataBayar
                        ]
                    ]);

    }

    public function notaPiutang(Request $request){
        $toko = $request->userinfo->userid;
        $piutang = Kartupiutang::select('ptg.kartupiutang.*', DB::raw('sum(ptg.kartupiutangdetail.nomtrans) as summarytransfer'))
                                ->join('ptg.kartupiutangdetail','ptg.kartupiutangdetail.kartupiutangid','=','ptg.kartupiutang.id')
                                ->where('ptg.kartupiutang.tokoid', $toko)
                                ->groupBy('ptg.kartupiutang.id')
                                ->get();
                                
        $saldopiutangJthTempo= $saldopiutangBlmJthTempo = 0;
        $tanggalBayarAkhir='';
        $dataPiutangjath = [];
        $dataPiutang = [];
        $now = date('Y-m-d');
        $tigaBulan = date('Y-m-d', strtotime('-3 month', strtotime($now)));
        if(count($piutang) > 0){
            foreach ($piutang as $key => $value) {
                if($value->nomnota - $value->summarytransfer > 0){
                    if($value->tgljt > $now){
                        $dataPiutang[] = ["nonota"=>$value->nonota,"nomnota"=>(double)$value->nomnota, "tgljt"=> date('d-m-Y', strtotime($value->tgljt)),"saldoPtg"=>($value->nomnota - $value->summarytransfer)];
                        $saldopiutangBlmJthTempo += $value->nomnota - $value->summarytransfer;
                    }elseif($value->tgljt < $now){
                        $dataPiutangjath[] = ["nonota"=>$value->nonota,"nomnota"=>(double)$value->nomnota, "tgljt"=>date('d-m-Y', strtotime($value->tgljt)),"saldoPtg"=>($value->nomnota - $value->summarytransfer)];
                        $saldopiutangJthTempo += $value->nomnota - $value->summarytransfer;
                    }

                }

            }
        }

        

        return response()->json([
                        'status'   =>'success',
                        'message'  =>'data piutang',
                        'data' => [
                            'nota'=>["blmjatuhtempo"=>$dataPiutang, "jatuhtempo"=>$dataPiutangjath]
                        ]
                    ]);

    }
}
