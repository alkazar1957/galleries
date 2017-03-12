<?php

//Route::resource('/galleries/', 'Alkazar\Gallery\Http\GalleryController@index');
	Route::get('/galleries', 'Alkazar\Gallery\Http\GalleryController@index')->middleware('web');
	Route::get('/galleries/{id}','Alkazar\Gallery\Http\GalleryController@showGallery')->middleware('web')->name('showGallery');
Route::group(['middleware' => ['web']], function () {
	Route::get('/galleries/delete/{id}','Alkazar\Gallery\Http\GalleryController@destroy');
	Route::get('/galleries/image/delete/{gallery}/{id}','Alkazar\Gallery\Http\GalleryController@destroyImage');
	Route::get('/galleries/image/edit/{gallery}/{id}/{direction}','Alkazar\Gallery\Http\GalleryController@editImage');
	Route::post('/galleries/save','Alkazar\Gallery\Http\GalleryController@saveGallery');
	Route::post('/galleries/image/upload','Alkazar\Gallery\Http\GalleryController@uploadImage');
});