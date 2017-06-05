<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpreadRecordModel extends Model
{
    protected $table      = 'dc_spread_record';

    protected $fillable   = ['openid','upper','ip','action','url','mark','source','stay','level'];

    public function user()
    {

        return $this->hasOne('App\Models\GrantUserModel','openid','openid');

    }

    protected $appends = [
        'level_name',
    ];

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
