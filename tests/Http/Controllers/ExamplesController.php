<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCrud\Tests\Http\Controllers\ExamplesController.
 */

namespace LushDigital\MicroServiceCrud\Tests\Http\Controllers;

use LushDigital\MicroServiceCrud\Http\Controllers\CrudController;

/**
 * An example controller to test with.
 *
 * @package LushDigital\MicroServiceCrud\Tests\Http\Controllers
 */
class ExamplesController extends CrudController
{
    /**
     * Override the namespace for the purposes of this test.
     *
     * @var string
     */
    protected $modelNamespace = 'LushDigital\\MicroServiceCrud\\Tests\\Models';
}