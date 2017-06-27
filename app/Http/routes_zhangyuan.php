<?php

//扩展模块
Route::group([

    'namespace'=>'Admin\Service',

    'prefix'=>'admin/service',

    'middleware'=> ['auth']

],function(){
    //\Auth::loginUsingId(1);
    //红包模块
    Route::resource('red_bag','RedBagController');

    //红包领取记录
    Route::resource('red_log','RedLogController');

    //广告栏
    Route::resource('ad_column','AdColumnController');

    //帮助中心
    Route::resource('help','HelpController');

});