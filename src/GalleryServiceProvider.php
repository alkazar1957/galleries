<?php

namespace Alkazar\Gallery;

use Illuminate\Support\ServiceProvider;

class GalleryServiceProvider extends ServiceProvider
{
   public function boot()
   {
   	
		// Load the Routes   	
		// require __DIR__ . '/routes/web.php';
   	$this->loadRoutesFrom(__DIR__.'/routes/web.php');

		// Define the path for the view blades   	
   	$this->loadViewsFrom(__DIR__ . '/Views', 'gallery');
   	
		// Publish the views for the user to over-ride    	
    	$this->publishes([ __DIR__.'/Views' => resource_path('views/vendor/gallery'),
    	], 'gallery-views');
    	
     	$this->loadMigrationsFrom(__DIR__.'/migrations');

		// Define which files are to be published and where to   	
    	$this->publishes([
   		__DIR__ . '/migrations/2016_01_16_000000_create_gallery_table.php' => base_path('database/migrations/2016_01_16_000000_create_gallery_table.php'),
   		__DIR__ . '/migrations/2016_01_16_000000_create_gallery_images_table.php' => base_path('database/migrations/2016_01_16_000000_create_gallery_images_table.php'),
   	], 'gallery-migrations');
   	
   	// Publish the public assets
   	$this->publishes([ __DIR__ . '/public/' => base_path('public'),
   	], 'gallery-assets');
   	
   }

	public function register() 
	{
		
		// Register the app;		
		$this->app['gallery'] = $this->app->share( function($app) {
			return new Gallery;	
		});	
	}
}