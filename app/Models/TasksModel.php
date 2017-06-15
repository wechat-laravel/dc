<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TasksModel extends Model
{
    protected $table      = 'dc_tasks';

    protected $fillable   = ['title','desc','img_url','page_url','mark'];

}
