<?php

namespace Exolnet\Image\Tests\Unit;

use Exolnet\Image\Image;
use Exolnet\Image\ImageService;
use Exolnet\Image\Repository\FilesystemRepository;
use Exolnet\Image\Tests\TestCase;
use Mockery as m;
use Symfony\Component\HttpFoundation\File\File;

class ImageServiceTest extends TestCase
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
     * @return void
     */
    public function tearDown(): void
    {
        if ($container = m::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        m::close();
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
    public function testUpdateImage()
    {
        $this->filesystemRepository->shouldReceive('destroy')->andReturn('true');
        $this->filesystemRepository->shouldReceive('store')->andReturn('true');

        $this->image->shouldReceive('getId')->once()->andReturn(1);
        $this->image->shouldReceive('save')->once();

        $this->file->shouldReceive('getBaseName')->once()->andReturn('test');
        $this->file->shouldReceive('guessExtension')->once()->andReturn('png');

        $this->service->updateImage($this->image, $this->file);
    }

    /**
     * @test
     * @return void
     */
    public function testUpdateImageByStateKeep()
    {
        $this->assertTrue($this->service->updateImageByState($this->image, 'keep', $this->file));
    }

    /**
     * @test
     * @return void
     */
    public function testUpdateImageByStateReplace()
    {
        $this->filesystemRepository->shouldReceive('destroy')->with($this->image)->once()->andReturn(true);
        $this->filesystemRepository->shouldReceive('store')->with($this->image, $this->file)->once()->andReturn(true);
        $this->assertTrue($this->service->updateImageByState($this->image, 'replace', $this->file));
    }

    /**
     * @test
     * @return void
     */
    public function testUpdateImageByStateNoFile()
    {
        $this->filesystemRepository->shouldReceive('destroy')->with($this->image)->once()->andReturn(true);
        $this->assertFalse($this->service->updateImageByState($this->image, 'replace'));

        $this->filesystemRepository->shouldReceive('destroy')->with($this->image)->once()->andReturn(true);
        $this->assertFalse($this->service->updateImageByState($this->image, ''));
    }

    /**
     * @test
     * @return void
     */
    public function testUpdateImageByStateNotKeepOrReplace()
    {
        $this->filesystemRepository->shouldReceive('destroy')->with($this->image)->once()->andReturn(true);
        $this->assertFalse($this->service->updateImageByState($this->image, '', $this->file));
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
     * @test
     * @return void
     * @throws \Exception
     */
    public function testDeleteImage()
    {
        $this->filesystemRepository->shouldReceive('destroy')->with($this->image)->andReturn(true);
        $this->image->shouldReceive('delete')->andReturn(true);
        $this->assertTrue($this->service->deleteImage($this->image));
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
