<?php
//for employee routes
use Illuminate\Support\Facades\Route;

Route::get("/home", 'HomeController@index')->name('employee.home');


Route::group(['middleware' => 'employeeProfile'], function () {

//posts
    Route::get('posts', 'PostController@index')->name('employee.post.index');
    Route::get('post/create', 'PostController@create')->name('employee.post.create');
    Route::post('post/create', 'PostController@store')->name('employee.post.store');
    Route::delete('post/destroy/{id}', 'PostController@destroy')->name('employee.post.destroy');


//Requests
    Route::get("/requests", 'RequestController@index')->name('employee.requests');
    Route::get("/request/{id}/accept", 'RequestController@accept')->name('employee.request.accept');
    Route::get('/request/{id}/reject', 'RequestController@reject')->name('employee.request.reject');

//Calendar
    Route::get("/calendar", 'CalendarController@index')->name('employee.calendar');
    Route::get("/calendar/{id}/show", 'CalendarController@show')->name('employee.calendar.show');
    Route::post("/calendar/{id}/receipt", 'CalendarController@addReceipt')->name('employee.calendar.add.receipt');
    Route::get("/calendar/{id}/reschedule", 'CalendarController@createReschedule')->name('employee.calendar.create.reschedule');


    //chat
    Route::get('/chat/{id}/index', 'ChatController@index')->name('employee.chat.index');
    Route::post('/chat/{id}/send', 'ChatController@send')->name('employee.chat.send');
    Route::get('/chat/{id}/load', 'ChatController@new')->name('employee.chat.new');


//Reviews
    Route::get("/reviews", 'HomeController@reviews')->name('employee.reviews');

//Route::get('/sss','ProfileController@clientProfile')->name('employee.client-profile');

});


//Profile
Route::get('/edit-profile', 'ProfileController@myProfile')->name('employee.profile');
Route::get('/profile/password', 'ProfileController@editPassword')->name('employee.password');
//Route::get('/profile/payment','ProfileController@editPayment')->name('employee.payment');
//Contact
Route::put('/edit-profile/contact', 'ProfileController@updateContact')->name('employee.contact.update');
Route::put('/edit-profile/connections', 'ProfileController@updateConnections')->name('employee.connections.update');
Route::put('/edit-profile/address', 'ProfileController@updateAddress')->name('employee.address.update');
Route::put('/edit-profile/biography', 'ProfileController@updateBiography')->name('employee.biography.update');
Route::put('/edit-profile/services', 'ProfileController@updateServices')->name('employee.services.update');

//Documents
Route::get('/profile/documents/edit', 'ProfileController@editDocuments')->name('employee.documents.edit');
Route::put('/profile/documents/edit/cv', 'ProfileController@updateCV')->name('employee.cv.update');
Route::put('/profile/documents/edit/cr', 'ProfileController@updateCR')->name('employee.cr.update');
Route::put('/profile/documents/edit/certificate', 'ProfileController@updateCertificate')->name('employee.certificate.update');

//Schedule
Route::get('/profile/schedule/edit', 'ProfileController@editSchedule')->name('employee.schedule.edit');
Route::put('/profile/schedule/edit', 'ProfileController@updateSchedule')->name('employee.schedule.update');
//Image
Route::put('/edit-profile/image/update', 'ProfileController@updateImage')->name('employee.image.update');
Route::delete('/edit-profile/image/destroy', 'ProfileController@destroyImage')->name('employee.image.destroy');



