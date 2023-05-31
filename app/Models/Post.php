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
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Post extends Model implements AbstractQueryable, Adaptable
{

	use Queryable;

	/**
	 * Model referenced table
	 * 
	 * @var string
	 */
	protected $table = 'posts';

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
		'type_id',
		'title',
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
		'id',
		'type',
		'comments',
	];

	/**
	 * Table primary key
	 * 
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 *
	 * @return BelongsTo
	 */
	public function id()
	{
		# code...
		return $this->belongsTo(\App\Models\PostTag::class, 'id', 'post_id');
	}

	/**
	 *
	 * @return BelongsTo
	 */
	public function type()
	{
		# code...
		return $this->belongsTo(\App\Models\PostType::class, 'type_id', 'id');
	}

	/**
	 *
	 * @return HasMany
	 */
	public function comments()
	{
		# code...
		return $this->hasMany(\App\Models\Comment::class, 'post_id', 'id');
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