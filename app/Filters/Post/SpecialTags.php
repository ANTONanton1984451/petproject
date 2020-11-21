<?php


namespace App\Filters\Post;



use App\Contracts\Decorator\Post\ConcreteHandler;
use App\Contracts\Decorator\Post\DecoratorFilter;
use App\Wrappers\NonHandledPostWrapper;

class SpecialTags implements DecoratorFilter
{
    private ConcreteHandler $handler;

    public function __construct(ConcreteHandler $handler)
    {
        $this->handler = $handler;
    }

    public function process(NonHandledPostWrapper $postWrapper): NonHandledPostWrapper
    {
        $this->handler->process($postWrapper);
        return $postWrapper;
    }
}
