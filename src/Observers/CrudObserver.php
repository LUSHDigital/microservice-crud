<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCrud\Observers\CrudObserver
 */

namespace LushDigital\MicroServiceCrud\Observers;

use LushDigital\MicroServiceModelUtils\Models\MicroServiceBaseModel;
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
     * @param MicroServiceBaseModel $model
     * @return void
     */
    public function saved(MicroServiceBaseModel $model)
    {
        $this->cacheForget($model);
    }

    /**
     * Listen to the model deleted event.
     *
     * @param MicroServiceBaseModel $model
     * @return void
     */
    public function deleted(MicroServiceBaseModel $model)
    {
        $this->cacheForget($model);
    }
}