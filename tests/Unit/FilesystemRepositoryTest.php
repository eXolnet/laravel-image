<?php

namespace Exolnet\Image\Tests\Unit;

use Exolnet\Image\Image;
use Exolnet\Image\Repository\FilesystemRepository;
use Exolnet\Image\Tests\TestCase;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;
use Symfony\Component\HttpFoundation\File\File;

class FilesystemRepositoryTest extends TestCase
{
    protected $filesystemRepository;

    protected $filesystem;

    public function setUp(): void
    {
        $this->filesystem = m::mock(Filesystem::class)->makePartial();
        $this->filesystemRepository = new FilesystemRepository($this->filesystem);
    }

    /**
     * @return void
     */
    public function testItIsInitializable()
    {
        $this->assertInstanceOf(FilesystemRepository::class, $this->filesystemRepository);
    }

    /**
     * @return void
     */
    public function testStore()
    {
        $file = m::mock(File::class);
        $image = m::mock(Image::class);

        $fakeName = '/src/image.png';
        $fakePath = 'image.png';

        $image->shouldReceive('getFileName')->once()->andReturn($fakeName);
        $image->shouldReceive('getImagePath')->twice()->andReturn($fakePath);

        $file->shouldReceive('move')->once()->withArgs(
            function ($fakePath, $fakeFileName) {
                return true;
            }
        )->andReturnSelf();

        $this->assertTrue($this->filesystemRepository->store($image, $file));
    }

    /**
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
     * @return void
     */
    public function testStorePathDoesNotExist(): void
    {
        $file = m::mock(File::class);
        $image = m::mock(Image::class);

        $fakeName = '/src/image.png';
        $fakePath = 'image.png';

        $image->shouldReceive('getFileName')->once()->andReturn($fakeName);
        $image->shouldReceive('getImagePath')->twice()->andReturn($fakePath);

        $this->filesystem->shouldReceive('exists')->with(dirname($fakePath))
            ->once()->andReturn(false);
        $this->filesystem->shouldReceive('makeDirectory')
            ->with(dirname($fakePath), 0755, true)->once()->andReturn(false);

        $file->shouldReceive('move')->once()->withArgs(
            function ($fakePath, $fakeFileName) {
                return true;
            }
        )->andReturnSelf();

        $this->assertTrue($this->filesystemRepository->store($image, $file));
    }

    /**
     * @return void
     */
    public function testStoreIsNotWritable(): void
    {
        $file = m::mock(File::class);
        $image = m::mock(Image::class);

        $fakeName = '/src/image.png';
        $fakePath = 'image.png';

        $this->expectExceptionMessage('The image base path "' . dirname($fakePath) . '" is not writable.');

        $image->shouldReceive('getFileName')->once()->andReturn($fakeName);
        $image->shouldReceive('getImagePath')->twice()->andReturn($fakePath);

        $this->filesystem->shouldReceive('isWritable')->once()->andReturn(false);

        $this->filesystemRepository->store($image, $file);
    }

    /**
     * @return void
     */
    public function testDestroy(): void
    {
        $image = m::mock(Image::class);
        $fakeFileName = 'image.png';
        $image->shouldReceive('getImagePath')->once()->andReturn($fakeFileName);

        $this->assertTrue($this->filesystemRepository->destroy($image));
    }

    /**
     * @return void
     */
    public function testDestroyDeleteImage(): void
    {
        $image = m::mock(Image::class);
        $fakePath = 'image.png';
        $image->shouldReceive('getImagePath')->twice()->andReturn($fakePath);
        $this->filesystem->shouldReceive('exists')->with($fakePath)->once()->andReturn(true);
        $this->filesystem->shouldReceive('delete')->with($fakePath)->once()->andReturn(true);

        $this->assertTrue($this->filesystemRepository->destroy($image));
    }
}
