<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Model\Registerdetail;
use App\Model\Registersubdetail;
use App\Model\Tokobayar;
use App\Model\Setoruang;

class Tokobayardetail extends Model {
    protected $connection = 'pgsql2';
    protected $table = 'bayar.tokobayardetail';
    const CREATED_AT = 'created_at_sync';
    const UPDATED_AT = 'updated_at_sync';

    public static function list($id){
    	$bayar = Tokobayardetail::select('bayar.tokobayar.tokoid','bayar.tokobayar.registerid', 'bayar.tokobayardetail.*')
                    ->join('bayar.tokobayar','bayar.tokobayar.id','=','bayar.tokobayardetail.tokobayarid')
                    ->where('bayar.tokobayar.collectorid',$id)
    				->get();
    	return $bayar;
    }

    public static function cekAmountUnpaid($idColector){
    	$list = Tokobayardetail::select(DB::raw('SUM(bayar.tokobayardetail.nominaltransaksi) as total_bayar'))
    			->join('bayar.tokobayar','bayar.tokobayar.id','=','bayar.tokobayardetail.tokobayarid')
    			->where('bayar.tokobayar.collectorid',$idColector)
    			->where('bayar.tokobayardetail.tipetransaksi','KAS')
    			->first();

    	$setoruang = Setoruang::select(DB::raw('SUM(bayar.setoruang.nominaltransfer) as total_setor'))
    							->where('collectorid', $idColector)->first();
    	return $list->total_bayar - $setoruang->total_setor;

    }

    public static function cekAmountUnpaidUpdate($idColector, $idSetor){
        $list = Tokobayardetail::select(DB::raw('SUM(bayar.tokobayardetail.nominaltransaksi) as total_bayar'))
                ->join('bayar.tokobayar','bayar.tokobayar.id','=','bayar.tokobayardetail.tokobayarid')
                ->where('bayar.tokobayar.collectorid',$idColector)
                ->where('bayar.tokobayardetail.tipetransaksi','KAS')
                ->first();

        $setoruang = Setoruang::select(DB::raw('SUM(bayar.setoruang.nominaltransfer) as total_setor'))
                                ->where('collectorid', $idColector)
                                ->where('bayar.setoruang.id','!=', $idSetor)
                                ->first();
        return $list->total_bayar - $setoruang->total_setor;

    }
}
