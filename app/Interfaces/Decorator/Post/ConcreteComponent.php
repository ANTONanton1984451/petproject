<?php


namespace App\Interfaces\Decorator\Post;


use App\Wrappers\NonProcessPostWrapper;

interface ConcreteComponent
{
    public function process(NonProcessPostWrapper $postWrapper):NonProcessPostWrapper;
}
