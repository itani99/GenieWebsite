<?php

use Illuminate\Support\Facades\Route;

Route::get("/home", 'HomeController@index')->name('admin.home');

Route::resource('service', 'ServiceController');
Route::resource('client', 'ClientController');
Route::resource('employee', 'HandymanController');
//
//Route::get('employee/show/{id}','HandymanController@show')->name('employee.profile.show');

Route::get('service.test', ['uses' => 'ServiceController@test', 'as' => 'service.test']);
Route::post('add-service','ServiceController@store')->name('admin.services');

//Route::resource('service', 'ServiceController');
//
//Route::post('service/update', 'ServiceController@update')->name('service.update');
//
//Route::get('service/destroy/{id}', 'ServiceController@destroy');
