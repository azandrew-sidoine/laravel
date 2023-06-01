<?php

/*
 * This file is auto generated using the Drewlabs Code Generator package (v2.3)
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Http\ViewModels;

use App\Models\PostTag;
use App\Dto\PostTagDto;
use Drewlabs\Contracts\Validator\ViewModel as AbstractViewModel;
use Drewlabs\Laravel\Http\Traits\HttpViewModel as ViewModel;
use Drewlabs\Laravel\Http\Traits\InteractsWithServerRequest;
use Drewlabs\Laravel\Http\Traits\AuthorizeRequest;
use Drewlabs\Validation\Traits\ModelAware;
use Drewlabs\Validation\Traits\ProvidesRulesFactory;
use Drewlabs\Validation\Traits\Validatable;
use Drewlabs\Laravel\Query\Traits\CreatesFilters;
use Illuminate\Http\Request;

final class PostTagViewModel implements AbstractViewModel
{

	use ViewModel;
	use InteractsWithServerRequest;
	use AuthorizeRequest;
	use ModelAware;
	use ProvidesRulesFactory;
	use Validatable;
	use CreatesFilters;

	/**
	 * Model class associated with the view model
	 * 
	 * @var string
	 */
	private $model_ = PostTag::class;

	/**
	 * Data transfer class associated with the view model
	 * 
	 * @var string
	 */
	private $dtoclass_ = PostTagDto::class;

	/**
	 * Class instance initializer
	 * 
	 * @param Request $request
	 *
	 * @return self
	 */
	public function __construct(Request $request = null)
	{
		# code...
		$this->bootInstance($request);
	}

	/**
	 * Returns a fluent validation rules
	 * 
	 *
	 * @return array<string,string|string[]>
	 */
	public function rules()
	{
		# code...
		return [
			'id' => ['sometimes', 'exists:post_tags,id', 'integer'],
			'post_id' => ['required_without:id', 'integer', 'exists:posts,id'],
			'tag_id' => ['required_without:id', 'integer', 'exists:tags,id'],
		];
	}

	/**
	 * Returns a list of validation error messages
	 * 
	 *
	 * @return array<string,string|string[]>
	 */
	public function messages()
	{
		# code...
		return [];
	}

	/**
	 * Returns a fluent validation rules applied during update actions
	 * 
	 *
	 * @return array<string,string|string[]>
	 */
	public function updateRules()
	{
		# code...
		return [
			'id' => ['sometimes', 'exists:post_tags,id', 'integer'],
			'post_id' => ['sometimes', 'integer', 'exists:posts,id'],
			'tag_id' => ['sometimes', 'integer', 'exists:tags,id'],
		];
	}

	/**
	 * returns the model class
	 * 
	 *
	 * @return string
	 */
	public function getModel()
	{
		# code...
		return $this->model_;
	}

	/**
	 * returns the list of queried columns
	 * 
	 *
	 * @return array
	 */
	public function getColumns()
	{
		# code...
		return $this->has('_columns') ? (is_array($columns = $this->get('_columns')) ? $columns : (@json_decode($columns, true) ?? ['*'])): ['*'];
	}

	/**
	 * returns the list of excluded columns
	 * 
	 *
	 * @return array
	 */
	public function getExcludes()
	{
		# code...
		return $this->has('_hidden') ? (is_array($columns = $this->get('_hidden')) ? $columns : (@json_decode($columns, true) ?? ['*'])): ['*'];
	}

}