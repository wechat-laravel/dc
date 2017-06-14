<?php

//扩展模块
Route::group([

    'namespace'=>'Admin\Service',

    'prefix'=>'admin/service',

],function(){

    //红包模块
    Route::resource('red_bag','RedBagController');

});