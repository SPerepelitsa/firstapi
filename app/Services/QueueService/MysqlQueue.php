<?php

namespace App\Services\QueueService;

use App\Services\QueueService\QueueInterfaces\StatQueueInterface;
use DB;
use App\Services\QueueService\Message;

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
        DB::beginTransaction();
        $row = DB::table(self::QUEUE_TABLE)
            ->select(['id', 'message'])
            ->where('queue_name', $this->queueName)
            ->where('in_progress', 0)
            ->lockForUpdate()
            ->first();
        if (!$row) {
            return null;
        }
        
        DB::table(self::QUEUE_TABLE)->where('id', $row->id)->update([
            'in_progress' => 1,
            'updated_at'  => date('Y-m-d H:i:s')
        ]);
        DB::commit();

        return new Message($row->id, $row->message);
    }
    
    public function deleteCompletedQueues($rowId)
    {
        DB::table(self::QUEUE_TABLE)->where('id', $rowId)->delete();
    }    
}
