<?php

namespace Exolnet\Image\Tests\Unit;

use Exolnet\Image\Image;
use Exolnet\Image\Tests\UnitTest;

class ImageTest extends UnitTest
{
    /**
     * @var \Exolnet\Image\Image
     */
    protected $model;

    public function setUp(): void
    {
        $this->model = new Image;
    }

    public function testItIsInitializable()
    {
        $this->assertInstanceOf(Image::class, $this->model);
    }

    public function testGetSetFileName()
    {
        $this->model->setFilename('test.png');
        $this->assertEquals('test.png', $this->model->getFilename());
    }
}
