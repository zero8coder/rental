<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('wx_users', WxUserController::class);
    $router->resource('rooms', RoomController::class);
    $router->resource('tenants', TenantController::class);
    $router->resource('room_tenants', RoomTenantController::class);
});
