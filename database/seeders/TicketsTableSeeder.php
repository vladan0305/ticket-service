<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('App\Model\Ticket');

        $status = ['Pending', 'In progress', 'Completed', 'Rejected'];

        for($i = 1 ; $i <= 200 ; $i++){
            $time = $faker->dateTimeBetween('-1 years', 'now', null);
        	DB::table('tickets')->insert([
        	'user_id' => User::where('is_admin', 0)->inRandomOrder()->first()->id,
        	'title' => $faker->words(3, true),
        	'message' => $faker->words(10, true),
            'status' => collect($status)->random(1)[0],
        	'created_at' => $time,
        	'updated_at' => $time,
        ]);
        }
    }
}
