<?php

namespace Database\Seeders;

use App\Jobs\HandlerPost;
use App\Wrappers\NonHandledPostWrapper;
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
        HandlerPost::dispatch(new NonHandledPostWrapper(1,1,1,1,))->delay(5);
    }
}
