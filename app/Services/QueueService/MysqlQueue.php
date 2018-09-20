<?php

namespace App\Services\QueueService;

use App\Services\QueueService\QueueInterfaces\StatQueueInterface;
use DB;

class MysqlQueue implements StatQueueInterface
{
private const QUEUE_TABLE = 'queues';

    private $queueName;

    public function __construct($queueName)
    {
        $this->queueName = $queueName;
    }

    /**
     * @param array $message
     *
     * @return bool
     */
    public function insertQueueMessage(array $message): bool
    {
        return DB::table(self::QUEUE_TABLE)
            ->insert(
                [
                    'queue_name' => $this->queueName,
                    'message'    => json_encode($message),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );
    }

    public function getQueueMessage()
    {
        $queue = DB::table(self::QUEUE_TABLE)
            ->select(['id', 'message'])
            ->where('queue_name', $this->queueName)
            ->where('in_progress', 0)
            ->first();
        if (!$queue) {
            return null;
        }

        DB::table(self::QUEUE_TABLE)->where('id', $queue->id)->update([
            'in_progress' => 1,
            'updated_at'  => date('Y-m-d H:i:s')
        ]);

        return json_decode($queue->message);
    }
}
