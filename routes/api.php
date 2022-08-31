<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

$api->version('v1', function ($api) {
    $api->get('/test', function () {
        return "Hello World";
    });

    $api->group(['prefix' => 'auth'], function ($api) {
        $api->POST('login', 'App\Http\Controllers\AuthController@login');
        $api->GET('me', 'App\Http\Controllers\AuthController@me');
        $api->GET('refresh', 'App\Http\Controllers\AuthController@refresh');
    });

    $api->group(['middleware' => 'api'], function ($api) {


    });
});
