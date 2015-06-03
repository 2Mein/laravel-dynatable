# Laravel Dynatable for Laravel 5
A library for sending connecting to the [dynatables.js](http://www.dynatable.com/) front end.  

Credits to [ifnot](https://github.com/Ifnot/Dynatable) for making the original version of dynatables for laravel. 

## Installation
    composer require twomein/laravel-dynatables

## Usage

Register the service provider in the app.config

    'Twomein\LaravelDynatable\LaravelDynatableServiceProvider',


Example usage:

```php

    //Get an Eloquent collection
    $cars = Car::all();
    
    //Define the columns you want to send
    $columns = ['id', 'name', 'price', 'stock'];
    
    // Build dynatable response
    return Dynatable::make($cars, $columns, Input::all());
  }
}
```
### Inputs
To give a little bit of insights about the inputs parameter:

    'page-length' => (int)$inputs['perPage'],
    'page-number' => (int)$inputs['page'],
    'offset' => (int)$inputs['offset'],
    'sorts' => isset($inputs['sorts']) ? $inputs['sorts'] : null,
    'search' => isset($inputs['queries']['search']) ? $inputs['queries']['search'] : null,




