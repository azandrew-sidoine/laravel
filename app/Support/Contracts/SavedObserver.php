<?php

/*
 * This file is auto generated using the Drewlabs Code Generator package (v2.3)
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Support\Contracts;

use Drewlabs\Contracts\Validator\ViewModel;

interface SavedObserver
{
    /**
     * @template T
     * 
     * insert the request metadata into database
     * 
     * @param Model $req 
     * @param ViewModel $view
     * @param \Closure(...$args):T|null $callback
     * 
     * @return \Closure(...$args):T
     */
    public function saved(Model $req, ViewModel $view, ?\Closure $callback = null);
}
