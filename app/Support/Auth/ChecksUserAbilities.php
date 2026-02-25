<?php

namespace App\Support\Auth;

use App\Support\Auth\HasAnyAbility;
use App\Support\Auth\TokenCanAny;
use Drewlabs\Contracts\Auth\AuthorizationsAware;
use Drewlabs\Contracts\OAuth\HasApiTokens;
use Drewlabs\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as LvAuthenticatable;

trait ChecksUserAbilities
{

	/**
	 * Check if request user can update membership status
	 * 
	 * @param LvAuthenticatable|Authenticatable $user 
	 * @param array $abilities
	 * 
	 * @return bool 
	 */
	private function userHasAbilities($user, array $abilities)
	{
		if ($user instanceof HasApiTokens && TokenCanAny::new($abilities)->call($user)) {
			return true;
		}

		if ($user instanceof AuthorizationsAware && HasAnyAbility::new($abilities)->call($user)) {
			return true;
		}

		return false;
	}

}