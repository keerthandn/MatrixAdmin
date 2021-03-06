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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/','IndexController@index');
Route::match(['get','post'],'/admin','AdminController@login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Category Listing page
Route::get('/products/{url}','ProductsController@products');
// Product detail page
Route::get('/product/{id}','ProductsController@product');



Route::group(['middleware'=>['auth']],function(){
	Route::get('/admin/dashboard','AdminController@dashboard');
	Route::get('/admin/settings','AdminController@settings');
	Route::get('/admin/check-pwd','AdminController@chkPassword');
	Route::match(['get','post'],'/admin/update-pwd','AdminController@updatePassword');

	//Cateogories Routes(Admin)

		Route::match(['get','post'],'/admin/add-category','CategoryController@addCategory');
		Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory');
		Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory');
		Route::get('/admin/view-categories','CategoryController@viewCategories');

		//Product Routes

		Route::match(['get','post'],'/admin/add-product','ProductsController@addProduct');
		Route::get('/admin/view-products','ProductsController@viewProducts');
		Route::match(['get','post'],'/admin/edit-product/{id}','ProductsController@editProduct');
		
		Route::get('/admin/delete-product-image/{id}','ProductsController@deleteProductImage');
		Route::get('/admin/delete-product/{id}','ProductsController@deleteProduct');

		//Products Attributes Routes

		Route::match(['get','post'],'/admin/add-attributes/{id}','ProductsController@addAttributes');

		Route::get('/admin/delete-attribute/{id}','ProductsController@deleteAttribute');

});

Route::get('/logout','AdminController@logout');

