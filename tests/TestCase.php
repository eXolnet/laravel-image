<?php

namespace Exolnet\Image\Tests;

use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @return void
     */
    public function tearDown(): void
    {
        Mockery::close();
    }
}
