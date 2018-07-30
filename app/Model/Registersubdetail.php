<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;

class Registersubdetail extends Model {
    protected $connection = 'pgsql';
    protected $table = 'ptg.tagihansubdetail';

}
