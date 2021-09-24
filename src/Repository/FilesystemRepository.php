<?php namespace Exolnet\Image\Repository;

use Exolnet\Image\Imageable;
use \InvalidArgumentException;
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
     * @throws \InvalidArgumentException
     */
    public function store(Imageable $image, File $file): bool
    {
        if (! $image->getFilename()) {
            throw new InvalidArgumentException('Could not store image with an empty filename.');
        }

        $path     = dirname($image->getImagePath());
        $filename = basename($image->getImagePath());

        if (! $this->filesystem->exists($path)) {
            $this->filesystem->makeDirectory($path, 0755, true);
        }

        if (! $this->filesystem->isWritable($path)) {
            throw new InvalidArgumentException('The image base path "'. $path .'" is not writable.');
        }

        $file->move($path, $filename);

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
