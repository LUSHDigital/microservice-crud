<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCrud\Observers\CrudObserver
 */

namespace LushDigital\MicroServiceCrud\Observers;

use LushDigital\MicroServiceModelUtils\Contracts\Cacheable;
use LushDigital\MicroServiceModelUtils\Traits\MicroServiceCacheTrait;

/**
 * Observer class to act on CRUD item modifications.
 *
 * @package LushDigital\MicroServiceCrud\Observers.
 */
class CrudObserver
{
    use MicroServiceCacheTrait;

    /**
     * Listen to the model saved event.
     *
     * @param Cacheable $model
     * @return void
     */
    public function saved(Cacheable $model)
    {
        $this->cacheForget($model);
    }

    /**
     * Listen to the model deleted event.
     *
     * @param Cacheable $model
     * @return void
     */
    public function deleted(Cacheable $model)
    {
        $this->cacheForget($model);
    }
}
