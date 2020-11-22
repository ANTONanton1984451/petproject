<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\DomCrawler\Crawler;

class ParserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('parser',function (){
            return new Crawler();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
