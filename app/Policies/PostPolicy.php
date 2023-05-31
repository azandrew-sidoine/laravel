<?php

/*
 * This file is auto generated using the Drewlabs Code Generator package (v2.3)
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Post;
use App\Http\ViewModels\PostViewModel;

final class PostPolicy
{

	use HandlesAuthorization;

	/**
	 * Class instance initializer
	 * 
	 *
	 * @return self
	 */
	public function __construct()
	{
		# code...
	}

	/**
	 * `index` action policy gate
	 * 
	 * @param Authenticatable $user
	 * @param PostViewModel $view
	 *
	 * @return bool|mixed
	 */
	public function viewAny(Authenticatable $user = null, PostViewModel $view = null)
	{
		# code...
		return true;
	}

	/**
	 * `show` action policy gate handler
	 * 
	 * @param Authenticatable $user
	 * @param Post $model
	 * @param PostViewModel $view
	 *
	 * @return bool|mixed
	 */
	public function view(Authenticatable $user = null, Post $model = null, PostViewModel $view = null)
	{
		# code...
		if (null === $model) {
			$this->deny();
		}
		return true;
	}

	/**
	 * `store/create` action policy gate policy
	 * 
	 * @param Authenticatable $user
	 * @param PostViewModel $view
	 *
	 * @return bool|mixed
	 */
	public function create(Authenticatable $user = null, PostViewModel $view = null)
	{
		# code...
		return true;
	}

	/**
	 * `edit/update` action policy gate handler
	 * 
	 * @param Authenticatable $user
	 * @param Post $model
	 * @param PostViewModel $view
	 *
	 * @return bool|mixed
	 */
	public function update(Authenticatable $user = null, Post $model = null, PostViewModel $view = null)
	{
		# code...
		if (null === $model) {
			$this->deny();
		}
		return true;
	}

	/**
	 * `delete/destroy` action policy gate handler
	 * 
	 * @param Authenticatable $user
	 * @param Post $model
	 * @param PostViewModel $view
	 *
	 * @return bool|mixed
	 */
	public function delete(Authenticatable $user = null, Post $model = null, PostViewModel $view = null)
	{
		# code...
		if (null === $model) {
			$this->deny();
		}
		return true;
	}

}