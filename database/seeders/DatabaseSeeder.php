<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // /**
    //  * Seed the application's database.
    //  */
    // public function run(): void
    // {
    //     $this->call([
    //     SekolahSeeder::class,
    //     UserSeeder::class,
    //     ]);
    // }
    // //     $this->call(SekolahSeeder::class);
    // // }


    public function run(): void
    {
	        // User::factory(10)->create();
	
	        $this->call(SekolahSeeder::class);
	        $this->call(UserSeeder::class);
	        $this->call(SekolahDemoSeeder::class);
	}
}