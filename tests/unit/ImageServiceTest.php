<?php

use Exolnet\Image\ImageService;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;

class ImageServiceTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var \Exolnet\Image\ImageService
	 */
	protected $service;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $filesystem;

	public function setUp()
	{
		$this->filesystem = m::mock(Filesystem::class);

		$this->service = new ImageService($this->filesystem);
	}

	public function testItIsInitializable()
	{
		$this->assertInstanceOf(ImageService::class, $this->service);
	}
}
