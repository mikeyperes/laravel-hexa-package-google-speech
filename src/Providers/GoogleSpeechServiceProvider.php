<?php

namespace hexa_package_google_speech\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * GoogleSpeechServiceProvider -- registers Google Speech package views, routes, and settings.
 */
class GoogleSpeechServiceProvider extends ServiceProvider
{
    /**
     * Register services into the container.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/google-speech.php', 'google-speech');
    }

    /**
     * Bootstrap package resources.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/google-speech.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'google-speech');
        $this->registerSidebarItems();
    }

    /**
     * Push sidebar menu items and settings card into the core layout stacks.
     *
     * @return void
     */
    private function registerSidebarItems(): void
    {
        view()->composer('layouts.app', function ($view) {
            if (config('hexa.app_controls_sidebar', false)) return;
            $view->getFactory()->startPush('sidebar-menu', view('google-speech::partials.sidebar-menu')->render());
        });

        view()->composer('settings.index', function ($view) {
            $view->getFactory()->startPush('settings-cards', view('google-speech::partials.settings-card')->render());
        });
    }
}
