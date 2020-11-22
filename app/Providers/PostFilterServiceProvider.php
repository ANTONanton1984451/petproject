<?php

namespace App\Providers;


use App\Checkers\HttpOnlyLinks;
use App\DecoratorConcrete\Post\ModelBridge;
use App\Filters\Post\EmptyParagraphs;
use App\Filters\Post\SpecialTags;
use App\Jobs\HandlerPost;
use App\Wrappers\NonHandledPostWrapper;
use Illuminate\Support\ServiceProvider;

class PostFilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NonHandledPostWrapper::class,function (){
            return new NonHandledPostWrapper('','',0,0);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bindMethod(HandlerPost::class.'@handle',function ($job,$app){
            return $job->handle(new HttpOnlyLinks(new EmptyParagraphs(new SpecialTags(new ModelBridge()))));
        });
    }
}
