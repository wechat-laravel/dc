<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaptchaModel extends Model
{
    protected $table      = 'dc_captcha';

    protected $fillable   = ['user_id','mobile','vcode','email'];

    public function getCreatedAtAttribute($value)
    {
        return empty($value) ? '' : date('Y-m-d H:i:s',$value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return empty($value) ? '' : date('Y-m-d H:i:s',$value);
    }
}
