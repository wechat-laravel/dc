<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRemarkModel extends Model
{
    protected $table      = 'dc_users_remark';

    protected $fillable   = ['user_id','openid','name','age','sex','wechat_id','qq','mobile','remark'];

}
