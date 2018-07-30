<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RekapPenjualanMonthly extends Model
{
	protected $connection = 'pgsql3';
    protected $table = 'public.rekappenjualanperitemmonthly';
}
