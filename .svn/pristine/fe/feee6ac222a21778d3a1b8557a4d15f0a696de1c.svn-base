<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Model\Registerdetail;
use App\Model\Register;
use App\Model\Registersubdetail;
use App\Model\Idenpembayarantokobayardetail;

class Idenpembayaran extends Model {
    protected $connection = 'pgsql2';
    protected $table = 'bayar.idenpembayaran';
    const CREATED_AT = 'created_at_sync';
    const UPDATED_AT = 'updated_at_sync';

    public static function list($id){
    	$bayar = Idenpembayaran::where('kartupiutangid',$id)
    				->get();
    	return $bayar;
    }

    public static function daftar($id) {
        $reg = Register::select('id')->where('karyawanid', $id)->get()->toArray();
        $dt = [];
        if(count($reg) > 0){
            foreach($reg as $k => $v) {
                $dt[] = $v['id'];
            }
        }

        $data = Idenpembayaran::whereIn('registerid', $dt)
                ->get();
        $datareturn = [];
        if(count($data) > 0) {
            foreach ($data as $key => $value) {
                $value->idenpembayarantokobayardetail =  Idenpembayarantokobayardetail::where('idenpembayaranid', $value->id)->get();
                $datareturn[] = $value;
            }
        }
        return $data;
    }
}
