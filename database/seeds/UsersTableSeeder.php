<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $users=factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password','remember_token'])->toarray());

        $users= User::find(1);
        $users->name = 'Aufree';
        $users->email ='aufree@yousails.com';
        $users->password = bcrypt('123456');
        $users->is_admin = true;
        $users->activated = true;


        $users->save();


    }
}
