<?php namespace Exolnet\Image\Repository;

use Exolnet\Image\Imageable;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class FilesystemRepository
{
	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	private $filesystem;

	/**
	 * FilesystemRepository constructor.
	 *
	 * @param \Illuminate\Filesystem\Filesystem $filesystem
	 */
	public function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
	}

	public function store(Imageable $image, File $file)
	{

	}

	public function destroy(Imageable $image)
	{

	}
}
