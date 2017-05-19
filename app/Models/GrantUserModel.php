<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrantUserModel extends Model
{
    protected $table      = 'dc_grant_user';

    protected $fillable   = ['openid','name','avatar','email','sex','country','province','city','language'];
    
}
