<?php namespace Exolnet\Image;

use Illuminate\Database\Eloquent\Model;

class Image extends Model implements Imageable
{
	/**
	 * @return string
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * @param string $filename
	 * @return $this
	 */
	public function setFilename($filename)
	{
		$this->filename = $filename;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getImagePath()
	{
		return public_path('uploads/images/'. $this->getFilename());
	}

	/**
	 * @return string
	 */
	public function getImageUrl()
	{
		return asset('uploads/images/'. $this->getFilename());
	}
}
