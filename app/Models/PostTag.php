<?php

/*
 * This file is auto generated using the Drewlabs Code Generator package (v2.3)
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Models;

use Drewlabs\PHPValue\Contracts\Adaptable;
use Drewlabs\Query\Contracts\Queryable as AbstractQueryable;
use Illuminate\Database\Eloquent\Model;
use Drewlabs\Laravel\Query\Traits\Queryable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PostTag extends Model implements AbstractQueryable, Adaptable
{

	use Queryable;

	/**
	 * Model referenced table
	 * 
	 * @var string
	 */
	protected $table = 'post_tags';

	/**
	 * List of values that must be hidden when generating the json output
	 * 
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * List of attributes that will be appended to the json output of the model
	 * 
	 * @var array
	 */
	protected $appends = [];

	/**
	 * List of fillable properties of the current model
	 * 
	 * @var array
	 */
	protected $fillable = [
		'id',
		'post_id',
		'tag_id',
	];

	/**
	 * List of model relation methods
	 * 
	 * @var array
	 */
	public $relation_methods = [
		'post',
		'tag',
	];

	/**
	 * Table primary key
	 * 
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * Indicates whether table has updated_at and created_at columns 
	 * 
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * returns an eloquent `belongs to` relation
	 * 
	 *
	 * @return BelongsTo
	 */
	public function post()
	{
		# code...
		return $this->belongsTo(\App\Models\Post::class, 'post_id', 'id');
	}

	/**
	 * returns an eloquent `belongs to` relation
	 * 
	 *
	 * @return BelongsTo
	 */
	public function tag()
	{
		# code...
		return $this->belongsTo(\App\Models\Tag::class, 'tag_id', 'id');
	}

	/**
	 * Set `post_id` property to the parameter value
	 * 
	 * @param mixed $value
	 *
	 * @return static
	 */
	public function setPostId($value)
	{
		# code...
		$this->setAttribute('post_id', $value);
		return $this;
	}

	/**
	 * Get `post_id` property value
	 * 
	 *
	 * @return mixed
	 */
	public function getPostId()
	{
		# code...
		return $this->getAttribute('post_id');
	}

	/**
	 * Set `tag_id` property to the parameter value
	 * 
	 * @param mixed $value
	 *
	 * @return static
	 */
	public function setTagId($value)
	{
		# code...
		$this->setAttribute('tag_id', $value);
		return $this;
	}

	/**
	 * Get `tag_id` property value
	 * 
	 *
	 * @return mixed
	 */
	public function getTagId()
	{
		# code...
		return $this->getAttribute('tag_id');
	}

	/**
	 * Bootstrap the model and its traits.
	 * 
	 *
	 * @return void
	 */
	protected static function boot()
	{
		# code...
		parent::boot();
	}

}