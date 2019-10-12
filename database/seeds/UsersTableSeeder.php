<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            array('name' => 'admin','email' => 'admin@admin.com','password' => Hash::make('password'))
        );
        DB::table('users')->insert($users);
    }
}
