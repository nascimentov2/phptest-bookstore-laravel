<?php

namespace App\Workflows\Test\Activities;

use Workflow\Activity;

class TestActivity extends Activity
{
    public function execute(string $input): string
    {
        return "Input {$input} called!";
    }
}