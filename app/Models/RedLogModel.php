<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GrantUserModel;

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

    protected $appends = [
        'status_name'
    ];

    public function getStatusNameAttribute()
    {
        $value = $this->getAttribute('status');
        $status = '成功';
        if($value == 2){
            $status = '失败';
        }
        return $status;
    }

    public function info()
    {
        return $this->hasOne('App\Models\GrantUserModel', 'openid', 'open_id');
    }

}
