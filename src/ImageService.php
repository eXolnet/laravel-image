<?php namespace Exolnet\Image;

use Exolnet\Image\Repository\FilesystemRepository;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

class ImageService
{
    /**
     * @var \Exolnet\Image\Repository\FilesystemRepository
     */
    private $filesystemRepository;

    /**
     * ImageService constructor.
     *
     * @param \Exolnet\Image\Repository\FilesystemRepository $filesystemRepository
     */
    public function __construct(FilesystemRepository $filesystemRepository)
    {
        $this->filesystemRepository = $filesystemRepository;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @return \Exolnet\Image\Image
     */
    public function createImage(File $file)
    {
        $image = $this->makeImage();

        $fileName = Str::slug($file->getBasename()) .'.'. $file->guessExtension();

        $image->setFilename($fileName);
        $image->save();

        $image->setFilename($image->getId().'-'.$fileName);
        $image->save();

        $this->store($image, $file);

        return $image;
    }

    /**
     * @param \Exolnet\Image\Image                        $image
     * @param \Symfony\Component\HttpFoundation\File\File $file
     */
    public function updateImage(Image $image, File $file)
    {
        $this->destroy($image);

        $fileName = $image->getId() .'-'. Str::slug($file->getBasename()) .'.'. $file->guessExtension();

        $image->setFilename($fileName);
        $image->save();

        $this->store($image, $file);
    }

    /**
     * @param \Exolnet\Image\Image $image
     * @return bool
     * @throws \Exception
     */
    public function deleteImage(Image $image)
    {
        $this->destroy($image);

        return $image->delete(); // TODO-FG: Move this to an Eloquent Repository.
    }

    /**
     * @param \Exolnet\Image\Imageable                    $image
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @return bool
     */
    public function store(Imageable $image, File $file)
    {
        return $this->filesystemRepository->store($image, $file);
    }

    /**
     * @param \Exolnet\Image\Imageable                    $image
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @return bool
     */
    public function replace(Imageable $image, File $file)
    {
        if (! $this->destroy($image)) {
            return false;
        }

        return $this->store($image, $file);
    }

    /**
     * @param \Exolnet\Image\Imageable $image
     * @return bool
     */
    public function destroy(Imageable $image)
    {
        return $this->filesystemRepository->destroy($image);
    }

    /**
     * @param \Exolnet\Image\Imageable                         $image
     * @param                                                  $state
     * @param \Symfony\Component\HttpFoundation\File\File|null $file
     * @return bool
     */
    public function updateImageByState(Imageable $image, $state, File $file = null)
    {
        if ($state === 'keep') {
            return true;
        }

        $this->destroy($image);

        if ($state === 'replace' && $file) {
            return $this->store($image, $file);
        }

        return false;
    }

    /**
     * @return \Exolnet\Image\Image
     */
    protected function makeImage(): Image
    {
        return new Image();
    }
}
