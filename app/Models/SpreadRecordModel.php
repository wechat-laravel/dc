<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpreadRecordModel extends Model
{
    protected $table      = 'dc_spread_record';

    protected $fillable   = ['openid','tasks_id','upper','ip','action','url','mark','source','stay','level'];

}
