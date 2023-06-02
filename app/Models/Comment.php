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

final class Comment extends Model implements AbstractQueryable, Adaptable
{

	use Queryable;

	/**
	 * Model referenced table
	 *
	 * @var string
	 */
	protected $table = 'comments';

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
		'content',
		'created_at',
		'updated_at',
	];

	/**
	 * List of model relation methods
	 *
	 * @var array
	 */
	public $relation_methods = [
		'post',
	];

	/**
	 * Table primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

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
	 * Set `content` property to the parameter value
	 *
	 * @param mixed $value
	 *
	 * @return static
	 */
	public function setContent($value)
	{
		# code...
		$this->setAttribute('content', $value);
		return $this;
	}

	/**
	 * Get `content` property value
	 *
	 *
	 * @return mixed
	 */
	public function getContent()
	{
		# code...
		return $this->getAttribute('content');
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
