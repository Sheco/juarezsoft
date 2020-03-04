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

    Route::get('/users/nomina', 'UsersController@nomina')
        ->name('users.nomina');
});
