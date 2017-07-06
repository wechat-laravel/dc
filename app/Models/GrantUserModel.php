<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrantUserModel extends Model
{
    protected $table      = 'dc_grant_user';

    protected $fillable   = ['openid','name','avatar','email','sex','country','province','city','language'];

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
