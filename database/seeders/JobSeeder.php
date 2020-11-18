<?php

namespace Database\Seeders;

use App\Jobs\ProcessPost;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProcessPost::dispatch('test','test',1,1)->delay(5);
    }
}
