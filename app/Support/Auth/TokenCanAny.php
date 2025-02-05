<?php

namespace App\Support\Auth;

use Drewlabs\Contracts\OAuth\HasApiTokens;

class TokenCanAny
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
     * Checks if provided user has any of the scopes initialized
     * with this instance
     * 
     * @param HasApiTokens $user 
     * @param array $missing 
     * @return bool 
     */
    public function call(HasApiTokens $user): bool
    {
        foreach ($this->scopes as $scope) {
            if ($user->tokenCan($scope)) {
                return true;
            }
        }
        return false;
    }
}