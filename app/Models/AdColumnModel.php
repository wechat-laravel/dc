<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdColumnModel extends Model
{
    protected $table      = 'dc_ad_column';

    protected $fillable   = ['user_id','name','title','url','litimg','mark'];

}
