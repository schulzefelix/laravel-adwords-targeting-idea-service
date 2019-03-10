<?php

namespace SchulzeFelix\AdWords\Tests\Integration;

use SchulzeFelix\AdWords\Exceptions\InvalidConfiguration;

class AdWordsServiceProviderTest extends TestCase
{
    public function testItWillThrowAnExceptionIfNoDeveloperTokenIsSet()
    {
        $this->app['config']->set('adwords-targeting-idea-service.developer_token', '');

        $this->expectException(InvalidConfiguration::class);

        dd(\AdWords::searchVolumes(['cheesecake']));
    }
}
