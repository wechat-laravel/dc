<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedLogModel extends Model
{
    protected $table = 'dc_red_log';

    protected $fillable = [
        'open_id',
        'tasks_id',
        'total_amount',
        'status',
        'return_code',
        'return_msg',
        'result_code',
        'err_code',
        'err_code_des',
        'mch_billno',
        'send_listid'
    ];

    protected $dateFormat = 'U';
}
