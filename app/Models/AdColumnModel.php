<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdColumnModel extends Model
{
    protected $table      = 'dc_ad_column';

    protected $fillable   = ['user_id','name','title','label','share','mobile','url','qrcode','chat_url','litimg',

                             'one_t','one_d','one_d_url','two_t','two_d','two_d_url','three_t','three_d','three_d_url','mark'];

    protected $appends = [

        'mark_name',

    ];

    public function getMarkNameAttribute()
    {
        if (isset($this->attributes['mark'])) {

            $mark = $this->attributes['mark'];

            if ($mark === 1){

                return '留言模板';

            }else{

                return '自定义模板';

            }

        }else{

            return '';

        }

    }


}
