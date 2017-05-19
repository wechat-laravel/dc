<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpreadRecordModel extends Model
{
    protected $table      = 'dc_spread_record';

    protected $fillable   = ['openid','url','action','upper','level'];

}
