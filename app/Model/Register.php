<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Model\Registerdetail;
use App\Model\Registersubdetail;

class Register extends Model {
    protected $connection = 'pgsql';
    protected $table = 'ptg.tagihan';

    public static function list($id){
    	$register = Register::select("noreg","tglreg","wilayah","id")
    				->where('karyawanid',$id)
    				->where('statusaktif','t')
    				->where('approvalkasir','f')
    				->get();

        if(count($register) > 0){
            foreach ($register as $key => $value) {
                $value->tglreg = date("d-m-Y", strtotime($value->tglreg));
            }
        }
    	return $register;
    }
}
