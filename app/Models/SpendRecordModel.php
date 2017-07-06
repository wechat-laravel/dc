<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpendRecordModel extends Model
{
    protected $table      = 'dc_spend_record';

    protected $fillable   = ['user_id','mark','tasks_id','openid','money','remark','send_name','wishing','act_name'];

}
