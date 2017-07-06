<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRemarkModel extends Model
{
    protected $table      = 'dc_users_remark';

    protected $fillable   = ['user_id','openid','name','age','sex','wechat_id','qq','mobile','remark'];

    protected $appends = [

        'sex_name',

    ];

    public function getSexNameAttribute()
    {
        if (isset($this->attributes['sex'])) {

            $sex = $this->attributes['sex'];

            if ($sex === 0){

                return '不详';

            }elseif ($sex === 1){

                return '男';

            }else{

                return '女';

            }

        }else{

            return '';

        }

    }

}
