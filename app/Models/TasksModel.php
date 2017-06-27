<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TasksModel extends Model
{
    protected $table      = 'dc_tasks';

    protected $fillable   = ['title','desc','img_url','page_url','qrcode_url','mark','user_id','editorValue','is_ad','ad_column_id'];

    public function ad()
    {
        
        return $this->hasOne('App\Models\AdColumnModel','id','ad_column_id');

    }

}
