<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeRecordModel extends Model
{

    protected $table = 'dc_recharge_record';

    protected $fillable   = ['user_id','user_email','auth_id','money','remark'];

}
