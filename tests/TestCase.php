<?php

namespace Weiwenhao\StateMachine\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}