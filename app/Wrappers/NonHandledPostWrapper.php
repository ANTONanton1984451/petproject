<?php


namespace App\Wrappers;


class NonHandledPostWrapper
{
    private string $postBody;
    private string $postTitle;
    private int $communityId;
    private int $userId;

    public function __construct(string $postBody, string $postTitle, int $communityId, int $userId)
    {
        $this->postBody = $postBody;
        $this->postTitle = $postTitle;
        $this->communityId = $communityId;
        $this->userId = $userId;
    }

    public function setPostBody(string $postBody)
    {
        $this->postBody = $postBody;
    }

    public function setPostTitle(string $postTitle)
    {
        $this->postTitle = $postTitle;
    }

    public function setCommunityId(int $communityId)
    {
        $this->communityId = $communityId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCommunityId() : int
    {
        return $this->communityId;
    }

    public function getPostBody(): string
    {
        return $this->postBody;
    }

    public function getPostTitle(): string
    {
        return $this->postTitle;
    }
}
