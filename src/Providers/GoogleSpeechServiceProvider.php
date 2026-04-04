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

        // Sidebar links — registered via PackageRegistryService with auto permission checks
        if (!config('hexa.app_controls_sidebar', false)) {
            $registry = app(\hexa_core\Services\PackageRegistryService::class);
            $registry->registerSidebarLink('settings.google-speech', 'Google Speech', 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z', 'Google Speech', 'google-speech', 60);
        }

        // Settings card on /settings page
        $this->registerSettingsCard();
    }

    /**
     * Register settings card on the core settings page.
     *
     * @return void
     */
    private function registerSettingsCard(): void
    {
        view()->composer('settings.index', function ($view) {
            $view->getFactory()->startPush('settings-cards', view('google-speech::partials.settings-card')->render());
        });
    
        // Documentation
        if (class_exists(\hexa_core\Services\DocumentationService::class)) {
            app(\hexa_core\Services\DocumentationService::class)->register('google-speech', 'Google Speech', 'hexawebsystems/laravel-hexa-package-google-speech', [
                ['title' => 'Overview', 'content' => '<p>Google Cloud Speech-to-Text API integration for voice transcription.</p>'],
            ]);
        }
}
}
