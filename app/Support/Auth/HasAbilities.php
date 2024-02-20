<?php

namespace App\Support\Auth;

use Drewlabs\Contracts\Auth\AuthorizationsAware;

class HasAbilities
{
    /**
     * @var array
     */
    private $abilities = [];

    /**
     * Creates new class instance
     * 
     * @param array $abilities 
     */
    public function __construct(array $abilities = [])
    {
        $this->abilities = $abilities ?? [];
    }

    /**
     * New instance factory function
     * 
     * @param array $scopes 
     * @return static 
     */
    public static function new(array $scopes = [])
    {
        return new static($scopes);
    }

    /**
     * Checks if provided user has any of the scopes initialized
     * with this instance
     * 
     * @param AuthorizationsAware $user 
     * @return bool 
     */
    public function call(AuthorizationsAware $user, array &$missing): bool
    {
        $abilities = $user->getAuthorizations() ?? [];
        $missing = [];
        foreach ($this->abilities as $ability) {
            if (in_array($ability, $abilities)) {
                continue;
            }
            $missing[] = $ability;
        }

        if (empty($missing)) {
            return true;
        }

        return false;
    }
}
