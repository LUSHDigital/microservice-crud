<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCrud\Tests\Http\Controllers\NotesController.
 */

namespace LushDigital\MicroServiceCrud\Tests\Http\Controllers;

use LushDigital\MicroServiceCrud\Http\Controllers\CrudController;

/**
 * An example controller to test with.
 *
 * @package LushDigital\MicroServiceCrud\Tests\Http\Controllers
 */
class NotesController extends CrudController
{
    /**
     * The base class of the model related to this CRUD controller.
     *
     * @var string
     */
    protected $modelBaseClass = 'TodoNote';

    /**
     * Override the namespace for the purposes of this test.
     *
     * @var string
     */
    protected $modelNamespace = 'LushDigital\\MicroServiceCrud\\Tests\\Models';
}