<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpreadPeopleModel extends Model
{
    protected $table      = 'dc_spread_people';

    protected $fillable   = ['name','openid','upper','source','level','level_num','people_ids','people_num','read_num','read_at','read_time','sex','province','city'];

    protected $appends = [

        'sex_name',

        'level_name',

    ];

    public function user()
    {

        return $this->hasOne('App\Models\GrantUserModel','openid','openid');

    }

    public function upp()
    {

        return $this->hasOne('App\Models\GrantUserModel','openid','upper');

    }

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

    public function getLevelNameAttribute()
    {
        if (isset($this->attributes['level'])){

            $name = $this->attributes['level'];

            if ($name === 1){

                return '第一级';

            }elseif ($name === 2){

                return '第二级';

            }elseif ($name === 3){

                return '第三级';

            }elseif ($name === 4){

                return '第四级';

            }elseif ($name === 5){

                return '第五级';

            }elseif ($name === 6){

                return '第六级';

            }elseif ($name === 7){

                return '第七级';

            }elseif ($name === 8){

                return '第八级';

            }elseif ($name === 9){

                return '第九级';

            }else{

                return '第十级';

            }

        }else{

            return '';
        }

    }

}
