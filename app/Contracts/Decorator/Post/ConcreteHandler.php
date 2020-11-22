<?php


namespace App\Contracts\Decorator\Post;


use App\Wrappers\NonHandledPostWrapper;

interface ConcreteHandler
{
    public function process(NonHandledPostWrapper $postWrapper) : void;
}
