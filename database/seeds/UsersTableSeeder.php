<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
        	[
        		'name' => 'Krutik Patel',
        		'email' => 'kkrutikk@gmail.com',	
        		'password' => bcrypt('123456')
        	],
        	[
        		'name' => 'Ronak Luhar',
        		'email' => 'ronak@gmail.com',	
        		'password' => bcrypt('1234567')
        	]
        ];

        User::insert($users);
    }
}
