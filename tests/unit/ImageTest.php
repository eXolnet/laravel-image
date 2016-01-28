<?php

use Exolnet\Image\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
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
