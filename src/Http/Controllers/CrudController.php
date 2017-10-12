<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCrud\Http\Controllers\CrudController.
 */

namespace LushDigital\MicroServiceCrud\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Controller as BaseController;
use LushDigital\MicroServiceCrud\Exceptions\CrudModelException;
use LushDigital\MicroServiceCore\Traits\MicroServiceJsonResponseTrait;

/**
 * Abstract controller class for CRUD.
 *
 * @package LushDigital\MicroServiceCrud\Http\Controllers
 */
abstract class CrudController extends BaseController
{
    use MicroServiceJsonResponseTrait;

    /**
     * Fully qualified name of the expected base model class.
     *
     * @var string
     */
    const MICROSERVICE_BASE_MODEL_CLASS = 'LushDigital\\MicroServiceModelUtils\\Models\\MicroServiceBaseModel';

    /**
     * Fully qualified name of the expected crud model interface.
     *
     * @var string
     */
    const MICROSERVICE_CRUD_MODEL_INTERFACE = 'LushDigital\\MicroServiceCrud\\Models\\CrudModelInterface';

    /**
     * The base class of the model related to this CRUD controller.
     *
     * @var string
     */
    protected $modelBaseClass;

    /**
     * The namespace of the model related to this CRUD controller.
     *
     * @var string
     */
    protected $modelNamespace;

    /**
     * The table name associated with a model.
     *
     * @var string
     */
    protected $modelTableName;

    /**
     * CrudController constructor.
     *
     * @throws CrudModelException
     */
    public function __construct()
    {
        // Validate the model class.
        $this->validateModelClass($this->getModelClass());

        // Get the expected model table name.
        $this->modelTableName = call_user_func([$this->getModelClass(), 'getTableName']);
    }

    /**
     * Get all items.
     *
     * @return Response
     */
    public function index()
    {
        // Check the cache for data. Otherwise get from the db.
        $items = Cache::rememberForever($this->modelTableName . ':index', function () {
            return call_user_func([$this->getModelClass(), 'all'])->toArray();
        });

        if (empty($items)) {
            return $this->generateResponse($this->modelTableName, null, Response::HTTP_NO_CONTENT);
        }

        return $this->generateResponse($this->modelTableName, $items);
    }

    /**
     * Create a new item.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Instantiate a new model item.
        $modelClass = $this->getModelClass();
        $item = new $modelClass;

        // Validate the request.
        $this->validate($request, $item->getValidationRules());

        // Create the new item.
        $itemData = $request->all();
        $item->fill($itemData);
        $item->save();

        $newItem = call_user_func([$this->getModelClass(), 'find'], $item->id)->toArray();
        return $this->generateResponse($this->modelTableName, [$newItem]);
    }

    /**
     * Get a single item by it's ID.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        // Check the cache for item data. Otherwise get from the db.
        $item = Cache::rememberForever($this->modelTableName . ':' . $id, function () use ($id) {
            return call_user_func([$this->getModelClass(), 'findOrFail'], $id)->toArray();
        });

        return $this->generateResponse($this->modelTableName, [$item]);
    }

    /**
     * Update the specified item.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Get the item.
        $item = call_user_func([$this->getModelClass(), 'findOrFail'], $id);

        // Validate the request.
        $this->validate($request, $item->getValidationRules('update', $id));
        $itemData = $request->all();

        // Update the item.
        $item->fill($itemData);
        $item->save();

        return $this->generateResponse($this->modelTableName, [$item->toArray()]);
    }

    /**
     * Delete a single item.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        // Get the item.
        $item = call_user_func([$this->getModelClass(), 'findOrFail'], $id);

        // Delete the item.
        $item->delete();

        return $this->generateResponse($this->modelTableName, null, 200, 'ok', 'Item was deleted successfully.');
    }

    /**
     * Get the fully qualified class of the model related to this controller.
     *
     * @return string
     * @throws CrudModelException
     */
    public function getModelClass()
    {
        $model = $this->getModelNamespace() . '\\' . $this->getModelBaseClass();

        // Validate the model class.
        $this->validateModelClass($model);

        return $model;
    }

    /**
     * Get the name of the model related to this CRUD controller.
     *
     * @return string
     */
    public function getModelBaseClass()
    {
        // Use the non-plural version of the controller name if no model
        // is already specified.
        if (empty($this->modelBaseClass)) {
            $this->modelBaseClass = Str::singular(str_replace('Controller', '', class_basename($this)));
        }

        return $this->modelBaseClass;
    }

    /**
     * Get the namespace of the model related to this CRUD controller.
     * @return string
     */
    public function getModelNamespace()
    {
        // Default namespace.
        if (empty($this->modelNamespace)) {
            $this->modelNamespace = 'App\\Models';
        }

        return $this->modelNamespace;
    }

    /**
     * Validate a model class name.
     *
     * @param $modelClass
     *
     * @return void
     * @throws CrudModelException
     */
    protected function validateModelClass($modelClass)
    {
        // Validate the expected model exists.
        if (!class_exists($modelClass)) {
            throw new CrudModelException('The related model does not exist.');
        }

        // Validate the model extends the correct base model.
        if (!is_subclass_of($modelClass, self::MICROSERVICE_BASE_MODEL_CLASS)) {
            throw new CrudModelException('The related model must extend the MicroServiceBaseModel abstract class.');
        }

        // Validate the model implements the correct interface.
        if (!in_array(self::MICROSERVICE_CRUD_MODEL_INTERFACE, class_implements($modelClass))) {
            throw new CrudModelException('The related model must implement CrudModelInterface.');
        }
    }
}
