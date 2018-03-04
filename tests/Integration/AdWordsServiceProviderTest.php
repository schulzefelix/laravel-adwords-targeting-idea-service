<?php

namespace SchulzeFelix\AdWords\Tests\Integration;

use SchulzeFelix\AdWords\Exceptions\InvalidConfiguration;

class AdWordsServiceProviderTest extends TestCase
{
    /** @test */
    public function it_will_throw_an_exception_if_no_developer_token_is_set()
    {
        $this->app['config']->set('adwords-targeting-idea-service.developer_token', '');

        $this->expectException(InvalidConfiguration::class);

        \AdWords::searchVolumes(['cheesecake']);
    }
}
