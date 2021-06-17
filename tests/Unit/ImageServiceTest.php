<?php

namespace Exolnet\Image\Tests\Unit;

use Exolnet\Image\ImageService;
use Exolnet\Image\Repository\FilesystemRepository;
use Exolnet\Image\Tests\UnitTest;
use Mockery as m;

class ImageServiceTest extends UnitTest
{
    /**
     * @var \Exolnet\Image\ImageService
     */
    protected $service;

    /**
     * @var \Mockery\MockInterface
     */
    protected $filesystemRepository;

    public function setUp(): void
    {
        $this->filesystemRepository = m::mock(FilesystemRepository::class);

        $this->service = new ImageService($this->filesystemRepository);
    }

    public function testItIsInitializable()
    {
        $this->assertInstanceOf(ImageService::class, $this->service);
    }
}
