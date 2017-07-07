<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpendRecordModel extends Model
{
    protected $table      = 'dc_spend_record';

    protected $fillable   = ['user_id','mark','tasks_id','openid','money','remark','send_name','wishing','act_name'];

    protected $appends = [

        'mark_name',

    ];

    public function getMarkNameAttribute()
    {
        if (isset($this->attributes['mark'])) {

            $mark = $this->attributes['mark'];

            switch ($mark){

                case 'task':

                    return '充值红包任务';

                case 'reward':

                    return '红包奖励';

                case 'trun':

                    return '红包余额转出';

                default:

                    return '账户余额充值';

            }

        }else{

            return '';

        }

    }

    public function task()
    {

        return $this->hasOne('App\Models\TasksModel','id','tasks_id');

    }

    public function user()
    {

        return $this->hasOne('App\Models\GrantUserModel','openid','openid');

    }

}
