<?php

namespace Exolnet\Image\Test\Unit;

use Exolnet\Image\Image;
use Exolnet\Image\Tests\UnitTest;

class ImageTest extends UnitTest
{
	/**
	 * @var \Exolnet\Image\Image
	 */
	protected $model;

	public function setUp()
	{
		$this->model = new Image;
	}

	public function testItIsInitializable()
	{
		$this->assertInstanceOf(Image::class, $this->model);
	}
}
