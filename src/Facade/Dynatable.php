<?php namespace Twomein\LaravelDynatable\Facades;

use Illuminate\Support\Facades\Facade;

class Dynatable extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dynatable';
    }

}