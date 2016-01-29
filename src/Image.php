<?php namespace Exolnet\Image;

use \Illuminate\Database\Eloquent\Model;
use \DateTime;

class Image extends Model implements Imageable
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'image';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['filename'];

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

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

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updated_at;
	}
}
