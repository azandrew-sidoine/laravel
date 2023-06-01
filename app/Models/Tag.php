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

final class Tag extends Model implements AbstractQueryable, Adaptable
{

	use Queryable;

	/**
	 * Model referenced table
	 * 
	 * @var string
	 */
	protected $table = 'tags';

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
	];

	/**
	 * List of model relation methods
	 * 
	 * @var array
	 */
	public $relation_methods = [
		'postTags',
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
	 * returns an eloquent `has many` relation
	 * 
	 *
	 * @return HasMany
	 */
	public function postTags()
	{
		# code...
		return $this->hasMany(\App\Models\PostTag::class, 'tag_id', 'id');
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