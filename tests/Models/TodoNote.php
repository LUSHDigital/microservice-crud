<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCrud\Tests\Models\TodoNote.
 */

namespace LushDigital\MicroServiceCrud\Tests\Models;

use LushDigital\MicroServiceCrud\Models\CrudModelInterface;
use LushDigital\MicroServiceModelUtils\Models\MicroServiceBaseModel;

/**
 * An example model to test with.
 *
 * @package LushDigital\MicroServiceCrud\Tests\Models
 */
class TodoNote extends MicroServiceBaseModel implements CrudModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function getValidationRules($mode = 'create', $primaryKeyValue = null)
    {
        return [];
    }
}