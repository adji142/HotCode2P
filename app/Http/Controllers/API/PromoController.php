<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Config;
use Validator;
use App\Model\Promo;
use App\Model\RekapPenjualanMonthly;
use App\Model\Shop;
use App\Model\PointPiutang;
use App\Model\PointKupon;
use App\Model\Kartupiutang;

use Illuminate\Support\Facades\Crypt;
class PromoController extends Controller{
    public function promo(Request $request){
       
        $promo = Promo::limit(4)->get();
        $promos = [];
        if(count($promo) > 0){
            foreach ($promo as $key => $value) {
                $promos["image".$key] = ["namapromo"=>$value->namapromo, "keterangan"=>$value->keterangan, "url"=>$value->url];
            }
        }

        if(count($promos) > 0){
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'data found',
                        'data' => ["promo"=>$promos]
                    ]);    
        }else{
            return response()->json([
                        'status'   =>'fail',
                        'message'  =>'No data found',
                        'data' => []
                    ]);    
        }
        
    }

    public function getpoint(Request $request){
        $toko = Shop::select('kodetoko','tokoidwarisan')->where('id',$request->userinfo->userid)->first();
        $subtotal = RekapPenjualanMonthly::select('subtotalnettojual')
                ->where('kodetoko',$toko->tokoidwarisan)
                ->whereBetween('periode',array('201805','201812'))
                ->sum('subtotalnettojual');
                //dd($subtotal);

        $kp=Kartupiutang::select('ptg.kartupiutang.nonota','ptg.kartupiutang.nomnota','ptg.kartupiutang.tglterima','mstr.toko.kodetoko')->where('tokoid',$request->userinfo->userid)
            ->leftjoin('mstr.toko','ptg.kartupiutang.tokoid','=','mstr.toko.id')->first();
            
        $detail_piut = PointPiutang::where('kodetoko',$toko->kodetoko)->first();
        $detail_kupon = PointKupon::where('kodetoko',$toko->kodetoko)->first();

        if($subtotal >= 25000000){
            $point = ($subtotal/25000000) * 1;
            $point = (int)$point;
        } else{
            $point = 0;
        }

        $collection = collect($kp);

        $merged = $collection->merge(["point" => $point]);

        $merged->all();

        return response()->json([
                        'status'   =>'success',
                        'message'  =>'data point',
                        'data' => [
                            "detail_pj" => $merged,
                            "detail_piut" => $detail_piut,
                            "detail_kupon" => $detail_kupon
                        ]
                    ]);
    }
}
