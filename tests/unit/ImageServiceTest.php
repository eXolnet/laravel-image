<?php

use Exolnet\Image\ImageService;
use Exolnet\Image\Repository\FilesystemRepository;
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
	protected $filesystemRepository;

	public function setUp()
	{
		$this->filesystemRepository = m::mock(FilesystemRepository::class);

		$this->service = new ImageService($this->filesystemRepository);
	}

	public function testItIsInitializable()
	{
		$this->assertInstanceOf(ImageService::class, $this->service);
	}
}
