<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCrud\Tests\Models\Foo.
 */

namespace LushDigital\MicroServiceCrud\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LushDigital\MicroServiceCrud\Models\CrudModelInterface;

/**
 * An example model to test with.
 *
 * @package LushDigital\MicroServiceCrud\Tests\Models
 */
class Foo extends Model implements CrudModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function getValidationRules($mode = 'create', $primaryKeyValue = null)
    {
        return [];
    }
}