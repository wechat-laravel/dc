<?php

//扩展模块
Route::group([

    'namespace'=>'Admin\Service',

    'prefix'=>'admin/service',

],function(){
    //\Auth::loginUsingId(1);
    //红包模块
    Route::resource('red_bag','RedBagController');

    //红包领取记录
    Route::resource('red_log','RedLogController');

    //帮助中心
    Route::resource('help','HelpController');

});