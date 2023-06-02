<?php

/*
 * This file is auto generated using the Drewlabs Code Generator package (v2.3)
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Http\Controllers;

use Drewlabs\Http\Factory\OkResponseFactoryInterface;
use Drewlabs\PHPValue\Utils\SanitizeCustomProperties;
use App\Services\TagService;
use App\Http\ViewModels\TagViewModel;
use App\Dto\TagDto;
use Drewlabs\Contracts\Support\Actions\ActionHandler;
use Drewlabs\Contracts\Validator\Validator;

// Function import statements
use function Drewlabs\Laravel\Query\Proxy\useMapQueryResult;
use function Drewlabs\Laravel\Query\Proxy\CreateQueryAction;
use function Drewlabs\Laravel\Query\Proxy\SelectQueryAction;
use function Drewlabs\Laravel\Query\Proxy\UpdateQueryAction;
use function Drewlabs\Laravel\Query\Proxy\DeleteQueryAction;

final class TagsController
{

	/**
	 * Injected instance of MVC service
	 * 
	 * @var ActionHandler
	 */
	private $service = null;

	/**
	 * Injected instance of the validator class
	 * 
	 * @var Validator
	 */
	private $validator = null;

	/**
	 * Injected instance of the response handler class
	 * 
	 * @var OkResponseFactoryInterface
	 */
	private $response = null;

	/**
	 * Class instance initializer
	 * 
	 * @param Validator $validator
	 * @param OkResponseFactoryInterface $response
	 * @param ActionHandler $service
	 *
	 * @return self
	 */
	public function __construct(Validator $validator, OkResponseFactoryInterface $response, ActionHandler $service = null)
	{
		# code...
		$this->validator = $validator;
		$this->response = $response;
		$this->service = $service ?? new TagService();
	}

	/**
	 * Display or Returns a list of items
	 * @Route /GET /tags[/{$id}]
	 * 
	 * @param TagViewModel $viewModel
	 *
	 * @return mixed
	 */
	public function index(TagViewModel $viewModel)
	{
		# code...
		$viewModel->authorize('viewAny', [$viewModel->getModel(), $viewModel]);
		
		//#region Excepts & attributes
		$columns = $viewModel->getColumns();
		$excepts = $viewModel->getExcludes();
		$properties = (new SanitizeCustomProperties(true))($columns);
		//#endregion Excepts & attributes
		
		$result = $this->service->handle(
			$viewModel->has('per_page') ? SelectQueryAction(
				$viewModel->makeFilters(),
				(int)$viewModel->get('per_page'),
				$columns,
				$viewModel->has('page') ? (int)$viewModel->get('page') : null,
			) : SelectQueryAction(
				$viewModel->makeFilters(),
				$columns,
			),
			useMapQueryResult(function ($value)  use ($excepts, $properties) {
				return $value ? TagDto::new($value)->addProperties($properties)->mergeHidden(array_merge($excepts, $value->getHidden() ?? [])) : $value;
			})
		);
		return $this->response->create($result);
	}

	/**
	 * Display or Returns an item matching the specified id
	 * @Route /GET /tags/{$id}
	 * 
	 * @param TagViewModel $viewModel
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function show(TagViewModel $viewModel, $id)
	{
		# code...
		$viewModel->authorize('view', [$viewModel->find($id), $viewModel]);
		
		//#region Excepts & attributes
		$columns = $viewModel->getColumns();
		$excepts = $viewModel->getExcludes();
		$properties = (new SanitizeCustomProperties(true))($columns);
		//#endregion Excepts & attributes
		
		$result = $this->service->handle(
			SelectQueryAction($id, $columns),
			function ($value)  use ($excepts, $properties) {
				return null !== $value ? TagDto::new($value)->addProperties($properties)->mergeHidden(array_merge($excepts, $value->getHidden() ?? [])) : $value;
			}
		);
		return $this->response->create($result);
	}

	/**
	 * Stores a new item in the storage
	 * @Route /POST /tags
	 * 
	 * @param TagViewModel $viewModel
	 *
	 * @return mixed
	 */
	public function store(TagViewModel $viewModel)
	{
		# code...
		$viewModel->authorize('create', [$viewModel->getModel(), $viewModel]);
		
		$result = $viewModel->validate($this->validator, function () use ($viewModel) {
			return $this->service->handle(CreateQueryAction($viewModel, [
				// TODO: Uncomment the code below to support relations insertion
				//'relations' => $viewModel->get('_query.relations') ?? []
				'upsert_conditions' => $query['upsert_conditions'] ?? ($viewModel->has($viewModel->getPrimaryKey()) ?
					[$viewModel->getPrimaryKey() => $viewModel->get($viewModel->getPrimaryKey()),] : []),
			]), function ($value) {
				return null !== $value ? new TagDto($value) : $value;
			});
		});
		
		return $this->response->create($result);
	}

	/**
	 * Update the specified resource in storage.
	 * @Route /PUT /tags/{id}
	 * @Route /PATCH /tags/{id}
	 * 
	 * @param TagViewModel $viewModel
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function update(TagViewModel $viewModel, $id)
	{
		# code...
		
		$viewModel->authorize('update', [$viewModel->find($id), $viewModel]);
		
		$result = $viewModel->merge(["id" => $id])->validate($this->validator->updating(), function () use ($id, $viewModel) {
			return $this->service->handle(UpdateQueryAction($id, $viewModel, [
				// TODO: Uncomment the code below to support relations insertion
				//'relations' => $viewModel->get('_query.relations') ?? [],
			]), function ($value) {
				return null !== $value ? new TagDto($value) : $value;
			});
		});
		
		return $this->response->create($result);
	}

	/**
	 * Remove the specified resource from storage.
	 * @Route /DELETE /tags/{id}
	 * 
	 * @param TagViewModel $viewModel
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function destroy(TagViewModel $viewModel, $id)
	{
		# code...
		$viewModel->authorize('delete', [$viewModel->find($id), $viewModel]);
		
		$result = $this->service->handle(DeleteQueryAction($id));
		return $this->response->create($result);
	}

}