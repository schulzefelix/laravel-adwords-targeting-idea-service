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

    /**
     * Assert that the session has a given list of values.
     *
     * @param  array $bindings
     *
     * @return void
     */
    public function assertSessionHasAll(array $bindings)
    {
        // TODO: Implement assertSessionHasAll() method.
    }

    /**
     * Assert that the session has old input.
     *
     * @return void
     */
    public function assertHasOldInput()
    {
        // TODO: Implement assertHasOldInput() method.
    }

    /**
     * Assert that the session has a given list of values.
     *
     * @param  string|array $key
     * @param  mixed $value
     *
     * @return void
     */
    public function assertSessionHas($key, $value = null)
    {
        // TODO: Implement assertSessionHas() method.
    }

    /**
     * Set the session to the given array.
     *
     * @param  array $data
     *
     * @return void
     */
    public function session(array $data)
    {
        // TODO: Implement session() method.
    }

    /**
     * Assert that the session has errors bound.
     *
     * @param  string|array $bindings
     * @param  mixed $format
     *
     * @return void
     */
    public function assertSessionHasErrors($bindings = [], $format = null)
    {
        // TODO: Implement assertSessionHasErrors() method.
    }

    /**
     * Flush all of the current session data.
     *
     * @return void
     */
    public function flushSession()
    {
        // TODO: Implement flushSession() method.
    }
}
