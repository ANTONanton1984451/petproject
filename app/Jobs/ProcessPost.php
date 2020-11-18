<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $post_body;
    private string $post_title;
    private int $communityId;
    private int $userId;

    /**
     * Create a new job instance.
     *
     * @param string $post_body
     * @param string $post_title
     * @param string $communityId
     * @param int $userId
     */
    public function __construct(string $post_body,string $post_title,string $communityId,int $userId)
    {
        $this->post_body = $post_body;
        $this->post_title = $post_title;
        $this->communityId = $communityId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
                
    }
}
