<?php


namespace App\Checkers;


use App\Contracts\Decorator\Post\ConcreteHandler;
use App\Contracts\Decorator\Post\DecoratorHandler;
use App\Rules\HttpLink;
use App\Wrappers\NonHandledPostWrapper;
use Illuminate\Support\Facades\Validator;

class HttpOnlyLinks implements DecoratorHandler
{
    private ConcreteHandler $handler;
    public function __construct(ConcreteHandler $component)
    {
        $this->handler = $component;
    }

    public function process(NonHandledPostWrapper $postWrapper) :void
    {
        $validationResult = Validator::make(
            [
                'postBody'=>$postWrapper->getPostBody()
            ],
            [
                'postBody'=>new HttpLink
            ]);
        $this->handler->process($postWrapper);
    }

}
