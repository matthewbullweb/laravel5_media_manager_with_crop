<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Media; //Laravel5 model fix

class MediaTableSeeder extends Seeder {

    public function run()
    {
        /*DB::table('media')->delete();

        Media::create(array(
			'id' => '1',
			'filename' => '',
			'embed_code' => ''
		));*/

		$this->command->info('Media table seeded!');
		
    }

}