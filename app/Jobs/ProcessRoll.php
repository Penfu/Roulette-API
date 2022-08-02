<?php

namespace App\Jobs;

use App\Events\RollEvent;
use App\Models\Roll;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRoll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $roll;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Roll $roll)
    {
        $this->roll = $roll;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        broadcast(new RollEvent($this->roll))->toOthers();
    }
}
