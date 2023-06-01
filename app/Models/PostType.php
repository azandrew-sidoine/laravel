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
use Illuminate\Database\Eloquent\Relations\HasMany;

final class PostType extends Model implements AbstractQueryable, Adaptable
{

	use Queryable;

	/**
	 * Model referenced table
	 * 
	 * @var string
	 */
	protected $table = 'post_types';

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
		'label',
		'created_at',
		'updated_at',
	];

	/**
	 * List of model relation methods
	 * 
	 * @var array
	 */
	public $relation_methods = [
		'posts',
	];

	/**
	 * Table primary key
	 * 
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * returns an eloquent `has many` relation
	 * 
	 *
	 * @return HasMany
	 */
	public function posts()
	{
		# code...
		return $this->hasMany(\App\Models\Post::class, 'type_id', 'id');
	}

	/**
	 * Set `label` property to the parameter value
	 * 
	 * @param mixed $value
	 *
	 * @return static
	 */
	public function setLabel($value)
	{
		# code...
		$this->setAttribute('label', $value);
		return $this;
	}

	/**
	 * Get `label` property value
	 * 
	 *
	 * @return mixed
	 */
	public function getLabel()
	{
		# code...
		return $this->getAttribute('label');
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