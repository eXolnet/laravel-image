<?php namespace Exolnet\Image;

interface Imageable
{
	/**
	 * @return string
	 */
	public function getFilename();

	/**
	 * @param string $filename
	 * @return string
	 */
	public function setFilename($filename);

	/**
	 * @return string
	 */
	public function getImagePath();

	/**
	 * @return string
	 */
	public function getImageUrl();
}
