<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\Helper;

class User2 extends Model {
    protected $connection = 'pgsql2';
    protected $table = 'public.users';
}
