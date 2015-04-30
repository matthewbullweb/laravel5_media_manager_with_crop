<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User; //Laravel5 model fix

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create(array(
			'name' => 'Matthew',
			'email' => 'matthew@matthewbullweb.co.uk',
			'password' => Hash::make('abc123')
		));
		
		$this->command->info('User table seeded!');
    }

}