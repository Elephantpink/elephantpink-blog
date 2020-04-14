<?php

namespace EPink\Blog;

// use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Laravel\Passport\Passport;

class BlogServiceProvider extends ServiceProvider
{   
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
      $this->loadRoutesFrom(__DIR__ . '/routes.php');
      // $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
      // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'blog');

      $this->publishes([
          __DIR__.'/../database/migrations' => database_path('migrations'),
          __DIR__.'/../database/seeds' => database_path('seeds'),
          __DIR__.'/../resources/blog_demo_images' => base_path('storage/app/public/blog_demo_images')
      ], 'epink-blog-migrations');

      $this->publishes([
        __DIR__.'/../resources/js' => base_path('resources/js/blog'),
        __DIR__.'/../resources/sass' => base_path('resources/sass/blog'),
        __DIR__.'/../resources/svg' => base_path('resources/svg')
      ], 'epink-blog-assets');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      
    }

    /**
     * Register the package factories
     */
    protected function loadFactoriesFrom($path)
    {
      $this->app->make(Factory::class)->load($path);
    }
}