<?php


namespace App\Filters\Post;


use App\Contracts\Decorator\Post\ConcreteHandler;
use App\Contracts\Decorator\Post\DecoratorHandler;
use App\Wrappers\NonHandledPostWrapper;


class EmptyParagraphs implements DecoratorHandler
{
    private ConcreteHandler $handler;

    public function __construct(ConcreteHandler $handler)
    {
        $this->handler = $handler;
    }

    public function process(NonHandledPostWrapper $postWrapper) : void
    {
       $this->handler->process($postWrapper);
    }
}
