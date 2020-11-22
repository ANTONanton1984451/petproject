<?php


namespace App\Contracts\Decorator\Post;


use App\Wrappers\NonHandledPostWrapper;

interface DecoratorHandler extends ConcreteHandler
{
        public function __construct(ConcreteHandler $component);
        public function process(NonHandledPostWrapper $postWrapper);
}
