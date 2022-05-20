<?php

use think\Db;
use \think\Route;

//Route::any('/api/Index_Echos/echos','api.index.echos.echos');


Route::group('api/index', function() {
    Route::get('echos', '@api/index/echos/echos');//路由到操作方法

});
Route::group('api/admin', function() {
    Route::get('getmenu', '@api/admin/Adminapi/getmenu');//路由到操作方法

});

