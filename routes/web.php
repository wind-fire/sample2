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

/*Route::get('/', function () {
    return view('welcome');
});*/
/*静态页路由*/

use Illuminate\Support\Facades\Route;

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

/*注册用户*/
Route::get('signup', 'UsersController@create')->name('signup');
/*resource 方法  相当于以下方法
路由声明时必须使用 Eloquent 模型的单数小写格式来作为路由片段参数，User 对应 {user}
Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
路由声明时必须使用 Eloquent 模型的单数小写格式来作为路由片段参数，User 对应 {user}
新增resource方法遵循RESTful架构为用户资源生成路由，第一个参数为资源名称，第二个参数为控制器名称
*/

Route::resource('users', 'UsersController');

/*用户登录以及用户注销*/
Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');

/*激活账户,邮箱链接*/
Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

/*找回密码*/
//显示输入邮箱找回密码界面
Route::get('password/reset','AUTH\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//服务器邮箱向用户邮箱发送重设密码链接
Route::post('password/email','AUTH\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//用户点击重设密码链接，服务器返回重设密码界面
Route::get('password/reset/{token}','AUTH\ResetPasswordController@showResetForm')->name('password.reset');
//用户执行更新操作，提交用户更新数据
Route::post('password/reset','AUTH\ResetPasswordController@reset')->name('password.update');




