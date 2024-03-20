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
use Drewlabs\Validation\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

final class CommandsController
{

	/**
	 * Injected instance of the response handler class
	 * 
	 * @var OkResponseFactoryInterface
	 */
	private $response = null;

	/**
	 * Class instance initializer
	 * 
	 * @param OkResponseFactoryInterface $response
	 *
	 * @return self
	 */
	public function __construct(OkResponseFactoryInterface $response)
	{
		# code...
		$this->response = $response;
	}

	/**
	 * @Route /api/v1/command/call/{command}
	 * 
	 * @param Request $request 
	 * @return void 
	 * @throws ValidationException 
	 */
	public function __invoke(Request $request, string $command = null)
	{
		if (null !== $command) {
			$request = $request->merge(['command' => $command]);
		}

		if (null === $request->input('command')) {
			throw new ValidationException(['command' => ['command name is required']]);
		}
	
		$parameters = [];

		// Add arguments to the list of parameters if any
		foreach ($request->input('arguments') ?? [] as $key => $argument) {
			# code...
			$parameters[$key] = $argument;
		}

		// Add options to the list of parameters if any
		foreach ($request->input('options') ?? [] as $key => $option) {
			$parameters[sprintf("--%s", trim($key, '-'))] = $option;
		}

		// Call the command and return the command result
		$this->response->create(Artisan::call($request->input('command'), $parameters));
	}
}
