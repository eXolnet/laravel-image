<?php namespace Exolnet\Image\Repository;

use Exolnet\Core\Exceptions\ServiceValidationException;
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

	/**
	 * @param \Exolnet\Image\Imageable                    $image
	 * @param \Symfony\Component\HttpFoundation\File\File $file
	 * @return bool
	 * @throws \Exolnet\Core\Exceptions\ServiceValidationException
	 */
	public function store(Imageable $image, File $file)
	{
		$basePath = $image->getImageBasePath();

		if ( ! $this->filesystem->exists($basePath)) {
			$this->filesystem->makeDirectory($basePath, 0755, true);
		}

		if ( ! $this->filesystem->isWritable($basePath)) {
			throw new ServiceValidationException('The image base path "'. $basePath .'" is not writable.');
		}

		$file->move($basePath, $image->getFilename());

		return true;
	}

	/**
	 * @param \Exolnet\Image\Imageable $image
	 * @return bool
	 */
	public function destroy(Imageable $image)
	{
		if ($this->filesystem->exists($image->getImagePath())) {
			return $this->filesystem->delete($image->getImagePath());
		}

		return true;
	}
}
