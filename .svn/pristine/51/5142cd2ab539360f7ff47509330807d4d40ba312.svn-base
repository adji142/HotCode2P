<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Model\Registerdetail;
use App\Model\Registersubdetail;

class Appsetting extends Model {
    protected $connection = 'pgsql2';
    protected $table = 'history.appsetting';

    public static function url(){
    	$request = Appsetting::where('keyid', 'URL')->get();
    	$data = [];
    	if(count($request) > 0){
    		foreach ($request as $key => $value) {
    			$data[] = $value;
    		}
    	}
    	return $data; 
    }
}