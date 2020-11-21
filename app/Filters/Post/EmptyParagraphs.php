<?php


namespace App\Filters\Post;


use App\Contracts\Decorator\Post\ConcreteHandler;
use App\Contracts\Decorator\Post\DecoratorFilter;
use App\Wrappers\NonHandledPostWrapper;
use Symfony\Component\DomCrawler\Crawler;

class EmptyParagraphs implements DecoratorFilter
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
