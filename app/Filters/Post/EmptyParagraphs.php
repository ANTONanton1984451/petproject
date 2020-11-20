<?php


namespace App\Filters\Post;


use App\Interfaces\Decorator\Post\ConcreteComponent;
use App\Interfaces\Decorator\Post\Decorator;
use App\Wrappers\NonProcessPostWrapper;

class EmptyParagraphs implements Decorator
{
    private ConcreteComponent $filter;

    public function __construct(ConcreteComponent $filter)
    {
        $this->filter = $filter;
    }

    public function process(NonProcessPostWrapper $postWrapper): NonProcessPostWrapper
    {
        $this->filter->process($postWrapper);
        return $postWrapper;
    }
}
