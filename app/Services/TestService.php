<?php

namespace App\Services;

use App\Http\Controllers\TestController;

class TestService
{
    private $testRun;

    public function __construct()
    {
        $this->testRun = new TestController();
    }

    public function test_testRun()
    {
        $this->testRun->testMethod();
    }

}