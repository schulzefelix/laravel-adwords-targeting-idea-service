<?php

namespace SchulzeFelix\AdWords;

use Illuminate\Support\ServiceProvider;
use SchulzeFelix\AdWords\Commands\GenerateRefreshTokenCommand;
use SchulzeFelix\AdWords\Exceptions\InvalidConfiguration;

class AdWordsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/adwords-targeting-idea-service.php' => config_path('adwords-targeting-idea-service.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/adwords-targeting-idea-service.php', 'adwords-targeting-idea-service');

        $adwordsConfig = config('adwords-targeting-idea-service');

        $this->app->bind('command.adwords:token', GenerateRefreshTokenCommand::class);
        $this->commands([
            'command.adwords:token',
        ]);

        $this->app->bind(AdWordsService::class, function () use ($adwordsConfig) {
            return AdWordsServiceFactory::createForConfig($adwordsConfig);
        });

        $this->app->bind(AdWords::class, function () use ($adwordsConfig) {
            $this->guardAgainstInvalidConfiguration($adwordsConfig);

            $adWordsService = app(AdWordsService::class);

            return new AdWords($adWordsService);
        });

        $this->app->alias(AdWords::class, 'laravel-adwords-targeting-idea-service');
    }

    protected function guardAgainstInvalidConfiguration(array $adwordsConfig = null)
    {
        if (empty($adwordsConfig['developer_token'])) {
            throw InvalidConfiguration::developerTokenNotSpecified();
        }
    }
}
