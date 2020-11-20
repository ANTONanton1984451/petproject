<?php


namespace App\Interfaces\Decorator\Post;


interface Decorator extends ConcreteComponent
{
        public function __construct(ConcreteComponent $component);
}
