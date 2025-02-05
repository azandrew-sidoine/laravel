<?php

namespace App\Support\Auth;

use Drewlabs\Contracts\OAuth\HasApiTokens;

class TokenCan
{
    /**
     * @var array
     */
    private $scopes = [];

    /**
     * Creates new class instance
     * 
     * @param array $scopes 
     */
    public function __construct(array $scopes = [])
    {
        $this->scopes = $scopes ?? [];
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
     * Checks if provided user has all scopes initialized with this instance
     * 
     * @param HasApiTokens $user 
     * @param array $missing 
     * @return bool 
     */
    public function call(HasApiTokens $user, array &$missing): bool
    {
        // Reset missing array variable to empty array to avoid any
        // any issue 
        $missing = [];
        foreach ($this->scopes as $scope) {
            if ($user->tokenCan($scope)) {
                continue;
            }
            $missing[] = $scope;
        }
        if (empty($missing)) {
            return true;
        }

        return false;
    }
}
