<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCrud\Tests\Http\Controllers\FoosController.
 */

namespace LushDigital\MicroServiceCrud\Tests\Http\Controllers;

use LushDigital\MicroServiceCrud\Http\Controllers\CrudController;

/**
 * An example controller to test with.
 *
 * @package LushDigital\MicroServiceCrud\Tests\Http\Controllers
 */
class FoosController extends CrudController
{
    /**
     * Override the namespace for the purposes of this test.
     *
     * @var string
     */
    protected $modelNamespace = 'LushDigital\\MicroServiceCrud\\Tests\\Models';
}