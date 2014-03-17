<?php 

namespace Mochaka\Shopify;

use Config;
use Illuminate\Support\ServiceProvider;

class ShopifyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('mochaka/shopify');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('Shopify', 'Mochaka\Shopify\Facades\Shopify');
		});

        $this->app['shopify'] = $this->app->share(function($app)
        {
            return new Shopify(Config::get('shopify::url'),Config::get('shopify::apikey'),Config::get('shopify::password'));
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('shopify');
	}

}