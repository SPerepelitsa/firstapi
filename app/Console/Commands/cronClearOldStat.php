<?php

namespace App\Console\Commands;

use App\UserStat;
use Illuminate\Console\Command;
use App\Services\StatService;

class cronClearOldStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:clear-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all temporary users statistic older than one week period';

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
        $statService = new StatService();
        $statService->clearOldStat();
    }
}
