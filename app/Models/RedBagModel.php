<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedBagModel extends Model
{
    protected $table = 'dc_red_bag';

    protected $fillable = [
      'user_id', 'mch_id', 'wxappid', 'send_name', 'wishing', 'act_name', 'remark'
    ];
}
