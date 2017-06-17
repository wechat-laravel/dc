<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedLogModel extends Model
{
    protected $table = 'dc_red_log';

    protected $fillable = [
        'open_id',
        'tasks_id',
        'money',
    ];

    protected $dateFormat = 'U';
}
