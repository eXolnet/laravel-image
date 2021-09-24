<?php

namespace Exolnet\Image\Tests\Unit;

use Exolnet\Image\Image;
use Exolnet\Image\Repository\FilesystemRepository;
use Exolnet\Image\Tests\UnitTest;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;
use Symfony\Component\HttpFoundation\File\File;

class FilesystemRepositoryTest extends UnitTest
{
    protected $filesystemRepository;

    protected $filesystem;

    public function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->filesystemRepository = new FilesystemRepository($this->filesystem);
    }

    /**
     * @test
     * @return void
     */
    public function testItIsInitializable()
    {
        $this->assertInstanceOf(FilesystemRepository::class, $this->filesystemRepository);
    }

    public function testStore()
    {
        $file = m::mock(File::class);
        $image = m::mock(Image::class);

        $fakePath = '/src/image.png';
        $fakeFileName = 'image.png';

        $image->shouldReceive('getFileName')->once()->andReturn($fakePath);
        $image->shouldReceive('getImagePath')->twice()->andReturn($fakeFileName);

        $file->shouldReceive('move')->once()->withArgs(
            function ($fakePath, $fakeFileName) {
                return true;
            }
        )->andReturnSelf();

        $this->assertTrue($this->filesystemRepository->store($image, $file));
    }

    /**
     * @test
     * @return void
     */
    public function testStoreEmptyImageFileName(): void
    {
        $this->expectExceptionMessage('Could not store image with an empty filename.');
        $file = m::mock(File::class);
        $image = m::mock(Image::class);

        $emptyFileName = '';
        $image->shouldReceive('getFileName')->once()->andReturn($emptyFileName);

        $this->filesystemRepository->store($image, $file);
    }

    /**
     * @test
     * @return void
     */
    public function testDestroy(): void
    {
        $image = m::mock(Image::class);
        $fakeFileName = 'image.png';
        $image->shouldReceive('getImagePath')->once()->andReturn($fakeFileName);

        $this->assertTrue($this->filesystemRepository->destroy($image));
    }
}
