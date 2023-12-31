<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Workflows\Test\Workflows\TestWorkflow;
use Workflow\WorkflowStub;

class TestWorkflowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $workflow = WorkflowStub::make(TestWorkflow::class);
        
        $workflow->start('JOB for Workflow');
        
        while ($workflow->running());
            $workflow->output();
    }

    public function tags(): array
    {
        return ['test-workflow'];
    }
}
