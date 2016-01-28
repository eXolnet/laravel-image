<?php namespace Exolnet\Image;

use Illuminate\Filesystem\Filesystem;

class ImageService
{
	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	private $filesystem;

	/**
	 * ImageService constructor.
	 *
	 * @param \Illuminate\Filesystem\Filesystem $filesystem
	 */
	public function __construct(Filesystem $filesystem)
	{

		$this->filesystem = $filesystem;
	}

	public function createImage()
	{
		// TODO-AD: To complete <adeschambeault@exolnet.com>
	}

	public function updateImage()
	{
		// TODO-AD: To complete <adeschambeault@exolnet.com>
	}

	public function deleteImage()
	{
		// TODO-AD: To complete <adeschambeault@exolnet.com>
	}
}
