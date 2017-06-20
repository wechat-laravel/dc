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
        'get_limit',
        'amount',
        'money',
        'action',
        'begin_at',
        'end_at',
        'event'
    ];

    protected $dateFormat = 'U';

    protected $appends = [
        'action_name',
        'taxonomy_name'
    ];

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

    public function getTaxonomyNameAttribute()
    {
        $value = $this->getAttribute('taxonomy');
        $taxonomy = '固定红包';
        if($value == 2){
            $taxonomy = '随机红包';
        }
        return $taxonomy;
    }

    public function getActionNameAttribute()
    {
        $value = $this->getAttribute('action');

        $action = strstr($value,',');

        if($action){
            return "分享给好友或朋友圈";
        }

        if($value == 1){
            return '分享给好友';
        }

        if($value == 2){
            return '分享到朋友圈';
        }
    }

}
