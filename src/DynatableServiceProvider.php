<?php namespace Twomein\LaravelDynatable;

use Illuminate\Support\ServiceProvider;

class DynatableServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('dynatable', 'Twomein\LaravelDynatable\Dynatable');
        /*
       * This removes the need to add a facade in the config\app
       */
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Dynatable', 'Twomein\LaravelDynatable\Facades\Dynatable');
        });
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
