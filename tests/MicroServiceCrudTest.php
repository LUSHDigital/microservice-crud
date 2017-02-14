<?php
/**
 * @file
 * Contains \MicroServiceCrudTest.
 */

namespace LushDigital\MicroServiceCrud\Tests;

use LushDigital\MicroServiceCrud\Exceptions\CrudModelException;
use LushDigital\MicroServiceCrud\Tests\Http\Controllers\ExamplesController;
use LushDigital\MicroServiceCrud\Tests\Http\Controllers\FoosController;
use LushDigital\MicroServiceCrud\Tests\Http\Controllers\NoModelController;
use LushDigital\MicroServiceCrud\Tests\Http\Controllers\NotesController;

/**
 * Test the MicroService CRUD functionality.
 */
class MicroServiceCrudTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Expected base model class name.
     *
     * @var string
     */
    protected $expectedModelBaseClass = 'Example';

    /**
     * Expected model namespace.
     *
     * @var string
     */
    protected $expectedModelNamespace = 'LushDigital\\MicroServiceCrud\\Tests\\Models';

    /**
     * Expected base model table name.
     *
     * @var string
     */
    protected $expectedModelTableName = 'examples';

    /**
     * Test the related models to a controller.
     */
    public function testControllerRelatedModel()
    {
        $examplesController = new ExamplesController();

        // Test the expected model class.
        $this->assertEquals($this->expectedModelBaseClass, $examplesController->getModelBaseClass());

        // Test the expected model namespace.
        $this->assertEquals($this->expectedModelNamespace, $examplesController->getModelNamespace());

        // Test the expected model table name.
        $this->assertEquals($this->expectedModelTableName, $examplesController->getModelTableName());

        $notesController = new NotesController();
        $this->expectedModelBaseClass = 'TodoNote';
        $this->expectedModelTableName = 'todo_notes';

        // Test the expected model class (overridden).
        $this->assertEquals($this->expectedModelBaseClass, $notesController->getModelBaseClass());

        // Test the expected model namespace (overridden).
        $this->assertEquals($this->expectedModelNamespace, $notesController->getModelNamespace());

        // Test the expected model table name (overridden).
        $this->assertEquals($this->expectedModelTableName, $notesController->getModelTableName());
    }

    /**
     * Test the incorrect base model associated with a controller.
     */
    public function testIncorrectBaseModel()
    {
        $this->expectException(CrudModelException::class);
        $this->expectExceptionMessage('The related model must extend the MicroServiceBaseModel abstract class.');

        new FoosController();
    }

    /**
     * Test the a controller with no available model.
     */
    public function testNoModelController()
    {
        $this->expectException(CrudModelException::class);
        $this->expectExceptionMessage('The related model does not exist.');

        new NoModelController();
    }
}