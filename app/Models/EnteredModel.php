<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnteredModel extends Model
{
    protected $table      = 'dc_entered';

    protected $fillable   = ['tasks_id','openid','name','sex','mobile','remark'];

}
