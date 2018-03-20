<?php
namespace Huid\Sms\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    public function setUp()
    {
        \Mockery::globalHelpers();
    }
    public function tearDown()
    {
        \Mockery::close();
    }
}


