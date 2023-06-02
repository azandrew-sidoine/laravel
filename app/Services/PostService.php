<?php

/*
 * This file is auto generated using the Drewlabs Code Generator package (v2.3)
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Services;

use App\Models\Post;
use Drewlabs\Contracts\Support\Actions\ActionHandler;
use Drewlabs\Contracts\Support\Actions\Exceptions\InvalidActionException;
use Drewlabs\Contracts\Support\Actions\ActionResult;
use Drewlabs\Contracts\Support\Actions\Action;
use Closure;

// Function import statements
use function Drewlabs\Laravel\Query\Proxy\useActionQueryCommand;

final class PostService implements ActionHandler
{

	/**
	 * {@inheritDoc}
	 * 
	 * @param Action $action
	 * @param Closure $callback
	 *
	 * @throws InvalidActionException
	 *
	 * @return ActionResult
	 */
	public function handle(Action $action, Closure $callback = null)
	{
		# code...
		return useActionQueryCommand(Post::class)($action, $callback);
	}

}