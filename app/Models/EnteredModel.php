<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnteredModel extends Model
{
    protected $table      = 'dc_entered';

    protected $fillable   = ['tasks_id','openid','name','sex','mobile','remark'];

    protected $appends = [

        'sex_name',

    ];

    public function user()
    {

        return $this->hasOne('App\Models\GrantUserModel','openid','openid');

    }

    public function people()
    {
        return $this->hasOne('App\Models\SpreadPeopleModel','openid','openid');
    }

    public function getSexNameAttribute()
    {
        if (isset($this->attributes['sex'])) {

            $sex = $this->attributes['sex'];

           if ($sex === 1){

                return '男';

            }else{

                return '女';

            }

        }else{

            return '';

        }

    }


}
