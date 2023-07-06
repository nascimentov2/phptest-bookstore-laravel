<?php

namespace App\Workflows\Test\Workflows;

use App\Workflows\Test\Activities\TestActivity;
use Workflow\ActivityStub;
use Workflow\Workflow;

class TestWorkflow extends Workflow
{
    public function execute(string $input): mixed
    {
        $result = yield ActivityStub::make(TestActivity::class, $input); //one workflow can execute various activities using yield key
        
        return $result;
    }
}