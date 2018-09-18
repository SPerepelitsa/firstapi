<?php
namespace App\Services\QueueService\QueueInterfaces;

interface StatQueueInterface
{
    public function insertQueueMessage(array $message);
    public function getQueueMessage();
}