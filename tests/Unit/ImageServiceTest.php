<?php

namespace Exolnet\Image\Tests\Unit;

use Exolnet\Image\Image;
use Exolnet\Image\ImageService;
use Exolnet\Image\Repository\FilesystemRepository;
use Exolnet\Image\Tests\UnitTest;
use Mockery as m;
use Symfony\Component\HttpFoundation\File\File;

class ImageServiceTest extends UnitTest
{
    /** @var \Exolnet\Image\ImageService */
    protected $service;

    /** @var \Mockery\MockInterface */
    protected $filesystemRepository;

    /** @var \Mockery\Mock|\Exolnet\Image\ImageService */
    private $mockedService;

    /** @var \Mockery\Mock|\Symfony\Component\HttpFoundation\File\File */
    private $file;

    /** @var \Mockery\Mock|\Exolnet\Image\Image */
    private $image;

    public function setUp(): void
    {
        $this->filesystemRepository = m::mock(FilesystemRepository::class);

        $this->service = new ImageService($this->filesystemRepository);

        $this->mockedService = m::mock(ImageService::class)->makePartial();

        $this->file = m::mock(File::class)->makePartial();
        $this->image = m::mock(Image::class)->makePartial();
    }

    /**
     * @test
     * @return void
     */
    public function testItIsInitializable()
    {
        $this->assertInstanceOf(ImageService::class, $this->service);
    }

    /**
     * @test
     * @return void
     */
    public function testCreateImage()
    {
        $this->image->shouldReceive('save')->twice();

        $this->file->shouldReceive('getBaseName')->once()->andReturn('test');
        $this->file->shouldReceive('guessExtension')->once()->andReturn('png');

        $this->mockedService->shouldAllowMockingProtectedMethods()
            ->shouldReceive('makeImage')->once()->andReturn($this->image);
        $this->mockedService->shouldReceive('store')->once()
            ->with($this->image, $this->file)->andReturn(true);

        $this->assertEquals($this->image, $this->mockedService->createImage($this->file));
    }

    /**
     * @test
     * @return void
     */
    public function testDestroy()
    {
        $this->image = m::mock(Image::class)->makePartial();
        $this->mockedDestroy(true);
        $this->assertTrue($this->service->destroy($this->image));
    }

    /**
     * @test
     * @return void
     */
    public function testReplace()
    {
        $this->mockedDestroy(true);
        $this->mockedStore();
        $this->assertTrue($this->service->replace($this->image, $this->file));

        $this->mockedDestroy(false);
        $this->assertFalse($this->service->replace($this->image, $this->file));
    }

    /**
     * @param bool $valueExpected
     */
    public function mockedDestroy(bool $valueExpected)
    {
        $this->filesystemRepository->shouldReceive('destroy')->once()->with($this->image)->andReturn($valueExpected);
    }

    public function mockedStore()
    {
        $this->filesystemRepository->shouldReceive('store')->once()
            ->with($this->image, $this->file)->andReturn(true);
    }
}
