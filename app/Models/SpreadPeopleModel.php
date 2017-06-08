<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpreadPeopleModel extends Model
{
    protected $table      = 'dc_spread_people';

    protected $fillable   = ['name','openid','level','level_num','people_num','read_num','read_at','read_time','sex','province','city'];

}
