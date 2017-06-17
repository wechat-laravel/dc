<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedBagModel extends Model
{
    protected $table = 'dc_red_bag';

    protected $fillable = [
        'user_id',
        'mch_id',
        'wxappid',
        'send_name',
        'wishing',
        'act_name',
        'remark',
        'taxonomy',
        'tasks_id',
        'amount',
        'money',
        'action',
        'begin_at',
        'end_at',
        'event'
    ];

    protected $dateFormat = 'U';

    public function getBeginAtAttribute($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    public function getEndAtAttribute($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    public function title()
    {
        return $this->hasOne('App\Models\TasksModel','id','tasks_id');
    }

    public function getTaxonomyAttribute($value)
    {
        $taxonomy = '固定红包';
        if($value == 2){
            $taxonomy = '随机红包';
        }
        return $taxonomy;
    }

    public function getActionAttribute($value)
    {
        $action = strstr($value,',');

        if($action){
            return '转发给好友/群,分享到朋友圈';
        }

        if($value == 1){
            return '转发给好友/群';
        }

        if($value == 2){
            return '分享到朋友圈';
        }
    }

}
