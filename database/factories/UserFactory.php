<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
/*在实际的项目开发过程中，我们经常会用到一些假数据来对数据库进行填充以方便调试程序，原始的做法是手工一个个在数据库中创建，或者从队友的机器那导出数据填充到开发机器中。
Laravel 提供了一套更加现代化、非常简单易用的数据填充方案。接下来让我们使用 Laravel 提供的数据填充来批量生成假用户。
假数据的生成分为两个阶段：

对要生成假数据的模型指定字段进行赋值 - 『模型工厂』；
批量生成假数据模型 - 『数据填充』；
模型工厂
Laravel 默认为我们集成了 Faker 扩展包，使用该扩展包可以让我们很方便的生成一些假数据。*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    $date_time = $faker->date().' '.$faker->time();
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
//        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        /*?: PHP 三元运算符简写模式 $password ? $password=bcrypt($password) : ''*/
        'password' => $password ?:$password=bcrypt('123456'),
        'remember_token' => str_random(10),
        'created_at' =>$date_time,
        'updated_at' =>$date_time,
        'is_admin' =>false,
        'activated' =>true,
    ];
});
