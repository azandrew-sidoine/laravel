<?php

/*
 * This file is auto generated using the Drewlabs Code Generator package (v2.3)
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class PoliciesServiceProvider extends ServiceProvider
{

    /**
     * Map application models to policies
     *
     * @var array
     */
    private $policies = [];

    /**
     * Policies property getter
     *
     *
     * @return array
     */
    private function policies()
    {
        # code...
        return $this->policies;
    }

    /**
     * Register authorization policies.
     *
     *
     * @return void
     */
    private function registerPolicies()
    {
        # code...
        foreach ($this->policies() as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Boot application services.
     *
     *
     * @return void
     */
    public function boot()
    {
        # code...
        $this->registerPolicies();
    }
}
