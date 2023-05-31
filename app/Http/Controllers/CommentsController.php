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
use App\Services\CommentService;
use App\Http\ViewModels\CommentViewModel;
use App\Dto\CommentDto;
use Drewlabs\Contracts\Support\Actions\ActionHandler;
use Drewlabs\Contracts\Validator\Validator;

// Function import statements
use function Drewlabs\Laravel\Query\Proxy\useMapQueryResult;
use function Drewlabs\Laravel\Query\Proxy\CreateQueryAction;
use function Drewlabs\Laravel\Query\Proxy\SelectQueryAction;
use function Drewlabs\Laravel\Query\Proxy\UpdateQueryAction;
use function Drewlabs\Laravel\Query\Proxy\DeleteQueryAction;

final class CommentsController
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
		$this->service = $service ?? new CommentService();
	}

	/**
	 * Display or Returns a list of items
	 * @Route /GET /comments[/{$id}]
	 * 
	 * @param CommentViewModel $viewModel
	 *
	 * @return mixed
	 */
	public function index(CommentViewModel $viewModel)
	{
		# code...
		$viewModel->authorize('viewAny', [$viewModel->getModel(), $viewModel]);
		
		//#region Hidden & Columns attributes
		$columns = $viewModel->getColumns();
		$excepts = $viewModel->get('_hidden') ?? [];
		//#endregion Hidden & Columns attributes
		
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
			useMapQueryResult(function ($value)  use ($excepts, $columns) {
				return $value ? CommentDto::new($value)->addProperties($columns)->mergeHidden($excepts) : $value;
			})
		);
		return $this->response->create($result);
	}

	/**
	 * Display or Returns an item matching the specified id
	 * @Route /GET /comments/{$id}
	 * 
	 * @param CommentViewModel $viewModel
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function show(CommentViewModel $viewModel, $id)
	{
		# code...
		$viewModel->authorize('view', [$viewModel->find($id), $viewModel]);
		
		//#region Hidden & Columns attributes
		$columns = $viewModel->getColumns();
		$excepts = $viewModel->get('_hidden') ?? [];
		//#endregion Hidden & Columns attributes
		
		$result = $this->service->handle(
			SelectQueryAction($id, $columns),
			function ($value)  use ($excepts, $columns) {
				return null !== $value ? CommentDto::new($value)->addProperties($columns)->mergeHidden($excepts) : $value;
			}
		);
		return $this->response->create($result);
	}

	/**
	 * Stores a new item in the storage
	 * @Route /POST /comments
	 * 
	 * @param CommentViewModel $viewModel
	 *
	 * @return mixed
	 */
	public function store(CommentViewModel $viewModel)
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
				return null !== $value ? new CommentDto($value) : $value;
			});
		});
		
		return $this->response->create($result);
	}

	/**
	 * Update the specified resource in storage.
	 * @Route /PUT /comments/{id}
	 * @Route /PATCH /comments/{id}
	 * 
	 * @param CommentViewModel $viewModel
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function update(CommentViewModel $viewModel, $id)
	{
		# code...
		
		$viewModel->authorize('update', [$viewModel->find($id), $viewModel]);
		
		$result = $viewModel->merge(["id" => $id])->validate($this->validator->updating(), function () use ($id, $viewModel) {
			return $this->service->handle(UpdateQueryAction($id, $viewModel, [
				// TODO: Uncomment the code below to support relations insertion
				//'relations' => $viewModel->get('_query.relations') ?? [],
			]), function ($value) {
				return null !== $value ? new CommentDto($value) : $value;
			});
		});
		
		return $this->response->create($result);
	}

	/**
	 * Remove the specified resource from storage.
	 * @Route /DELETE /comments/{id}
	 * 
	 * @param CommentViewModel $viewModel
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function destroy(CommentViewModel $viewModel, $id)
	{
		# code...
		$viewModel->authorize('delete', [$viewModel->find($id), $viewModel]);
		
		$result = $this->service->handle(DeleteQueryAction($id));
		return $this->response->create($result);
	}

}