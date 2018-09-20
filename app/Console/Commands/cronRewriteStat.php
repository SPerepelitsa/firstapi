<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\QueueService\MysqlQueue;
use App\Services\StatService;

class cronRewriteStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:rewrite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rewrites temp user statistic after login to regular user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mysqlQueue = new MysqlQueue('stat_queue');
        $statService = new StatService();
        while ($message = $mysqlQueue->getQueueMessage()) {
            $statService->rewriteStatAfterLogin($message->user_id, $message->temp_user_id);
        }
    }
}
