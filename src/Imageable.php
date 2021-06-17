<?php namespace Exolnet\Image;

interface Imageable
{
    /**
     * @return string
     */
    public function getFilename();

    /**
     * @return string
     */
    public function getImageBasePath();

    /**
     * @return string
     */
    public function getImagePath();

    /**
     * @return string
     */
    public function getImageUrl();
}
