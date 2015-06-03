# Dynatable
Credits to ifnot for making a fluent version for dynatables

# Installation
    composer require ifnot/dynatable:dev-master

# Usage

```php
<?php
class MyController {
  public function dynatable()
  {
    // Get fluent collection of what you want to show in dynatable
    $cars = Car::all();
    $columns = ['id', 'name', 'price', 'stock'];
    
    // Build dynatable response
    return new Dynatable($cars, $columns, [])
      ->make();
  }
}
```
