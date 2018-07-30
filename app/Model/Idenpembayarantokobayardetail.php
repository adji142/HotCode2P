<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Model\Registerdetail;
use App\Model\Registersubdetail;

class Idenpembayarantokobayardetail extends Model {
    protected $connection = 'pgsql2';
    protected $table = 'bayar.idenpembayarantokobayardetail';
    // const CREATED_AT = 'createdon';
    // const UPDATED_AT = 'lastupdatedon';
    const CREATED_AT = 'created_at_sync';
    const UPDATED_AT = 'updated_at_sync';
}
