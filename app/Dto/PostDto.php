<?php

/*
 * This file is auto generated using the Drewlabs Code Generator package (v2.3)
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Dto;

use Drewlabs\PHPValue\Contracts\ValueInterface;
use Drewlabs\PHPValue\Traits\Castable;
use Drewlabs\PHPValue\Traits\ObjectAdapter;
use Drewlabs\PHPValue\Contracts\Adaptable;
use Drewlabs\Laravel\Query\Traits\URLRoutableAware;
use Drewlabs\PHPValue\Contracts\HiddenAware;
use Illuminate\Contracts\Routing\UrlRoutable;

/**
 * @property int id
 * @property mixed type_id
 * @property string title
 * @property mixed content
 * @property string created_at
 * @property string updated_at
 *  
 * @package App\Dto
 */
final class PostDto implements ValueInterface, UrlRoutable, HiddenAware
{

	use URLRoutableAware;
	use Castable;
	use ObjectAdapter;

	/**
	 * @var array
	 */
	private const __PROPERTIES__ = [
		'id',
		'type_id',
		'title',
		'content',
		'created_at',
		'updated_at',
	];

	/**
	 * @var array
	 */
	protected $__HIDDEN__ = [];

	/**
	 * @var array
	 */
	protected $__CASTS__ = [
		'type' => 'value:\App\Dto\PostTypeDto',
		'postTags' => 'collectionOf:\App\Dto\PostTagDto',
		'comments' => 'collectionOf:\App\Dto\CommentDto',
	];

	/**
	 * Creates class instance
	 * 	
	 * @param array|Adaptable|Accessible $adaptable
	 * 
	 * @param mixed $adaptable
	 */
	public function __construct($adaptable = null)
	{
		# code...
		$this->bootInstance(static::__PROPERTIES__, $adaptable);
	}

	/**
	 * returns properties cast definitions
	 * 
	 *
	 * @return array
	 */
	public function getCasts()
	{
		# code...
		return $this->__CASTS__ ?? [];
	}

	/**
	 * set properties cast definitions
	 * 
	 * @param array $values
	 *
	 * @return string[]
	 */
	public function setCasts(array $values)
	{
		# code...
		$this->__CASTS__ = $values ?? $this->__CASTS__ ?? [];
		return $this;
	}

	/**
	 * returns the list of hidden properties
	 * 
	 *
	 * @return string[]
	 */
	public function getHidden()
	{
		# code...
		return $this->__HIDDEN__ ?? [];
	}

	/**
	 * set properties hidden properties
	 * 
	 * @param array $values
	 *
	 * @return string[]
	 */
	public function setHidden(array $values)
	{
		# code...
		$this->__HIDDEN__ = $values;
		return $this;
	}

}