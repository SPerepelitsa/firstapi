<?php

namespace App\Services\QueueService;

// Message is a DTO class
class Message
{
    private $id;
    private $userId;
    private $tempUserId;

    public function __construct(int $messageId, string $messageJson)
    {
        $this->id = $messageId;
        $this->setMessageParams($messageJson);
    }

    private function setMessageParams($messageJson)
    {
        $message = json_decode($messageJson);
        $this->userId = $message->user_id;
        $this->tempUserId = $message->temp_user_id;
    }

    public function getMessageId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getTempUserId()
    {
        return $this->tempUserId;
    }
}