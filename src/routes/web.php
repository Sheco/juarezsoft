<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register'=>false]);

Route::get('/', 'HomeController@index')->name('home');


Route::group(['middleware' => [ 'can:ver reportes' ]], function() {
    Route::get('/ventas/diarias', 'VentasController@diarias')
        ->name('ventas.diarias');

    Route::get('/ventas/productos', 'VentasController@productos')
        ->name('ventas.productos');

    Route::get('/ventas/vendedores', 'VentasController@vendedores')
        ->name('ventas.vendedores');
});

Route::group(['middleware' => ['can:administrar usuarios']], function() {
    Route::get('/users/nomina', 'UsersController@nomina')
        ->name('users.nomina');
    Route::get('/users/{user}/roles', 'UsersController@roles')
        ->name('users.roles');
    Route::post('/users/{user}/addRole', 'UsersController@addRole')
        ->name('users.addRole');
    Route::post('/users/{user}/delRole', 'UsersController@delRole')
        ->name('users.delRole');
    Route::get('/users/{user}/ventas', 'UsersController@ventas')
        ->name('users.ventas');        

    Route::resource('/users', 'UsersController');

    Route::get('/ventas/{venta}', 'VentasController@ver')
        ->name('ventas.ver');
});

Route::group(['middleware' => ['can:administrar inventario']], function() {
    Route::resource('productos', 'ProductosController');
});
