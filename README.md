# Lush Digital - CRUD
A CRUD convenience layer for microservices built in Lumen.

## Description
This package is intended to provide a convenient layer on top of Lumen to streamline the process of developing a RESTful
CRUD microservice. It reduces the repetitive nature of writing controllers, models and CRUD logic over and over again.

## Package Contents
* An abstract controller which can be extended for quick RESTful CRUD logic.
* Cache clearing observer to ensure your data always appears as up-to-date as possible.

## Installation
Install the package as normal:

```bash
$ composer require lushdigital/microservice-crud
```

The package requires that the following changes are made to the Lumen config in `app/bootstrap.php`

```php
<?php

// Uncomment the line below to enable Facade support.
$app->withFacades();

// Uncomment the line below to enable Eloquent ORM support.
$app->withEloquent();

// Add the line below to load database config. This is required for caching to work.
$app->configure('database');
```

## Usage
To create a new CRUD resource first extend your model from `\LushDigital\MicroServiceModelUtils\Models\MicroServiceBaseModel`

```php
<?php 

namespace App\Models;

use LushDigital\MicroServiceModelUtils\Models\MicroServiceBaseModel;

class Example extends MicroServiceBaseModel {}
```

Next you need to create a controller which extends from `\LushDigital\MicroServiceCrud\Http\Controllers\CrudController`

```php
<?php 

namespace App\Http\Controllers;

use LushDigital\MicroServiceCrud\Http\Controllers\CrudController;

class ExamplesController extends CrudController {}
```

### Model
Note that above the model is called `Example` and the controller is called `ExamplesController`. This follows the
plural pattern you're used to with Laravel. Basically the controller name needs to be the plural version of the model
plus 'Controller'.

This can be changed by overriding the `$modelBaseClass` attribute in the controller:

```php
<?php 

namespace App\Http\Controllers;

use LushDigital\MicroServiceCrud\Http\Controllers\CrudController;

class ExamplesController extends CrudController 
{
    /**
     * The model associated with the CRUD controller.
     * 
     * @var string  
     */
    protected $modelBaseClass = 'NotAnExample';
}
```

### Model Namespace
The CRUD controller assumes the model exists in the `App\Models` namespace (e.g. `App\Controllers\ExamplesController => App\Models\Example`).

This can be changed by overriding the `$modelNamespace` attribute:

```php
<?php 

namespace App\Http\Controllers;

use LushDigital\MicroServiceCrud\Http\Controllers\CrudController;

class ExamplesController extends CrudController 
{
    /**
     * The model associated with the CRUD controller.
     * 
     * @var string  
     */
    protected $modelNamespace = 'My\\Awesome\\Namespace';
}
```

### Cache Attributes
The package provides support for caching data based on any attribute of a model. By default the package will cache data
using the id attribute only. This works using cache keys, so for an instance of the `Example` model with id 1 the
following cache keys will be set on read and cleared on update/delete:

```bash
examples:index
examples:1
```

> Note the `:index` cache key is always used for the indexing endpoint listing all model instances.

To cache based on other attributes just override the `$attributeCacheKeys` attribute in your model:

```php
<?php 

namespace App\Models;

use LushDigital\MicroServiceModelUtils\Models\MicroServiceBaseModel;

class Example extends MicroServiceBaseModel
{
    /**
     * A list of the model attributes that can be used as cache keys.
     *
     * @var array
     */
    protected $attributeCacheKeys = ['name'];
}
```

> So in this cache Example (ID: 1) with a name of 'test' would have a cache key of `examples:name:test`

### Routing
Once your controller and model are set up you need to configure routes. The package provides logic for the standard REST
endpoints (GET, POST, PUT, DELETE). You can use as many or as few as you like, an example of all routes would be:

```php
<?php

// routes/web.php
$app->get('/examples', 'ExamplesController@index');
$app->post('/examples', 'ExamplesController@store');
$app->get('/examples/{id}', 'ExamplesController@show');
$app->put('/examples/{id}', 'ExamplesController@update');
$app->delete('/examples/{id}', 'ExamplesController@destroy');
```