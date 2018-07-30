<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;

class Setoruang extends Model {
    protected $connection = 'pgsql2';
    protected $table = 'bayar.setoruang';
    const CREATED_AT = 'created_at_sync';
    const UPDATED_AT = 'updated_at_sync';

    public static function list($id){
    	$setoruang = Setoruang::where('collectorid',$id)->orderBy('tgltransfer', 'DESC')->get();
        if(count($setoruang) > 0){
            foreach ($setoruang as $key => $value) {
                $value->tgltransfer = date("d-m-Y", strtotime($value->tgltransfer));
            }
        }
    	return $setoruang;
    }

    public static function listtransfer($id){
    	$setoruang = Setoruang::where('collectorid',$id)
    				->whereNotNull('namabank')
                    ->orderBy('tgltransfer', 'DESC')
    				->get();
    	return $setoruang;
    }
}
