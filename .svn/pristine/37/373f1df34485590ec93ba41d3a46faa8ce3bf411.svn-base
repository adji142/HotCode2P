<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;

class Registerdetail extends Model {
    protected $connection = 'pgsql';
    protected $table = 'ptg.tagihandetail';

    public static function listKartupiutang($tagihanId){
    	$registerdetail = Registerdetail::select("ptg.tagihandetail.tagihanid","ptg.tagihandetail.kartupiutangid",'ptg.tagihandetail.id as detailId',"ptg.tagihandetail.rptagih","mstr.toko.namatoko","mstr.toko.kodetoko","mstr.toko.id as TokoIDWiser","mstr.toko.tokoidwarisan as tokoIDISA",'ptg.kartupiutang.tokoid','ptg.kartupiutang.nonota','ptg.kartupiutang.tgljt','ptg.kartupiutang.tglproforma')
    				->where('ptg.tagihandetail.tagihanid',$tagihanId)
    				->join('ptg.kartupiutang','ptg.kartupiutang.id','=','ptg.tagihandetail.kartupiutangid')
    				->join('mstr.toko','kartupiutang.tokoid','=','mstr.toko.id')
                    ->orderBy('ptg.kartupiutang.tgljt',  'ASC')
    				->get();

        // $registerdetail = DB::connection("pgsql")->select('select "ptg"."tagihandetail"."kartupiutangid", 
        //                     "ptg"."tagihandetail"."id" as "detailId", "ptg"."tagihandetail"."rptagih", 
        //                     "mstr"."toko"."namatoko", "mstr"."toko"."kodetoko",
        //                     (select sum(a."rptagih") 
        //                         from "ptg"."tagihandetail" as a 
        //                         where a."tagihanid" = "ptg"."tagihandetail"."tagihanid" GROUP BY a."tagihanid") as summary
        //                     from "ptg"."tagihandetail" 
        //                     inner join "ptg"."kartupiutang" on "ptg"."kartupiutang"."id" = "ptg"."tagihandetail"."kartupiutangid" 
        //                     inner join "mstr"."toko" on "kartupiutang"."tokoid" = "mstr"."toko"."id" 
        //                     where "ptg"."tagihandetail"."tagihanid" ='.$tagihanId);
    	return $registerdetail;
    }
}
