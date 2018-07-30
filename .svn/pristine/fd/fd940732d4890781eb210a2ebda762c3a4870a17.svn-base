<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Model\Registerdetail;
use App\Model\Registersubdetail;

class Requesttoko extends Model {
    protected $connection = 'pgsql2';
    protected $table = 'history.requesttoko';
    public $timestamps      = false;

    public static function listByToko($id){
    	$request = Requesttoko::where('tokoid', $id)->orderBy('id', 'desc')->get();
    	if(count($request) > 10){
    		$tglstart = $request[0]->tglrequest;
    		$tgl2 = date('Y-m-d', strtotime('-2 month', strtotime($tglstart)));
    		$request = Requesttoko::where('tokoid', $id)
    					->whereBetween('tglrequest', [$tgl2, $tglstart])
    					->orderBy('id', 'desc')->get();
    	}
        
    	return $request; 
    }
}
