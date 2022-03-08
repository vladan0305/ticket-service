<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('App\Model\User');

        // Add admin user
        DB::table('users')->insert([
        	'name' => "Administrator",
        	'email' => "admin@ticker-service.com",
        	'password' => bcrypt("admin1234"),
        	'is_admin' => 1,
        	'created_at' => $faker->dateTimeBetween('-1 years', 'now', null),
        ]);

        DB::table('users')->insert([
        	'name' => "Support",
        	'email' => "support@ticker-service.com",
        	'password' => bcrypt("support1234"),
        	'is_admin' => 1,
        	'created_at' => $faker->dateTimeBetween('-1 years', 'now', null),
        ]);

        for($i = 1 ; $i <= 40 ; $i++){
        	DB::table('users')->insert([
        	'name' => $faker->name(),
        	'email' => $faker->email(),
        	'password' => bcrypt("test1234"),
        	'is_admin' => 0,
        	'created_at' => $faker->dateTimeBetween('-1 years', 'now', null),
        ]);
        }
    }
}
