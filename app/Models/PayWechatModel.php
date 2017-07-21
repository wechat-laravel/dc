<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayWechatModel extends Model
{
    protected $table      = 'dc_pay_wechat';

    protected $fillable   = ['user_id','total_fee','out_trade_no','prepay_id','code_url','status','err_code_des','pay_time','remark'];

    protected $appends = [

        'status_name',

    ];

    public function getStatusNameAttribute()
    {
        if (isset($this->attributes['status'])) {

            $status = $this->attributes['status'];

            if ($status === 0){

                return '待支付';

            }elseif ($status === 1){

                return '支付成功';

            }else{

                return '支付失败';

            }

        }else{

            return '';

        }

    }
}
