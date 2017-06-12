<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpreadPeopleModel extends Model
{
    protected $table      = 'dc_spread_people';

    protected $fillable   = ['name','openid','upper','source','level','level_num','people_ids','people_num','read_num','read_at','read_time','sex','province','city'];

    protected $appends = [

        'sex_name',

    ];

    public function getReadAtAttribute()
    {
        if (isset($this->attributes['read_at'])) {

            return date('Y-m-d H:i',$this->attributes['read_at']);

        }else{

            return '';

        }

    }

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
