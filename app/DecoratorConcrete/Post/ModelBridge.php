<?php


namespace App\DecoratorConcrete\Post;


use App\Contracts\Decorator\Post\ConcreteHandler;
use App\Wrappers\NonHandledPostWrapper;

class ModelBridge implements ConcreteHandler
{
    public function process(NonHandledPostWrapper $postWrapper) : void
    {

    }
}
