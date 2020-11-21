<?php

namespace App\Jobs;

use App\Contracts\Decorator\Post\DecoratorFilter;
use App\Wrappers\NonHandledPostWrapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandlerPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private NonHandledPostWrapper $postWrapper;

    /**
     * Create a new job instance.
     *
     * @param NonHandledPostWrapper $postWrapper
     */
    public function __construct(NonHandledPostWrapper $postWrapper)
    {
       $this->postWrapper = $postWrapper;
    }

    /**
     * Execute the job.
     *
     * @param DecoratorFilter $filter
     * @return void
     */
    public function handle(DecoratorFilter $filter)
    {
            $filter->process($this->postWrapper);
    }
}
