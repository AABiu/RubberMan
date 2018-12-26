<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'middleware' => [],
], function ($api) {
    $api->group(['prefix' => 'fortunes','namespace' => 'App\Http\Controllers'], function ($api) {
        //初始化历史数据
        $api->get('/history', 'FortunesController@getFortuneCodeHistory');

        //初始化全部数据
        $api->get('/init', 'DataOriginsController@initData');

        // 计算数据
        $api->get('/open-fortune', 'FortunesController@getNewFortuneList');
    });
});