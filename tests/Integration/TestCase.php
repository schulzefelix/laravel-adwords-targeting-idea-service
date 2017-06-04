<?php

namespace SchulzeFelix\AdWords\Tests\Integration;

use SchulzeFelix\AdWords\AdWordsFacade;
use Orchestra\Testbench\TestCase as Orchestra;
use SchulzeFelix\AdWords\AdWordsServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AdWordsServiceProvider::class,
        ];
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'AdWords' => AdWordsFacade::class,
        ];
    }
}
