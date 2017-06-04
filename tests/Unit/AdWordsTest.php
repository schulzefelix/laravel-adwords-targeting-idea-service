<?php

namespace SchulzeFelix\AdWords\Tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use SchulzeFelix\AdWords\AdWords;
use SchulzeFelix\AdWords\AdWordsService;

class AdWordsTest extends TestCase
{
    /** @var \SchulzeFelix\AdWords\AdWordsService|\Mockery\Mock */
    protected $adWordsService;

    /** @var \SchulzeFelix\AdWords\AdWords */
    protected $adwords;

    public function setUp()
    {
        $this->adWordsService = Mockery::mock(AdWordsService::class);
        $this->adwords = new AdWords($this->adWordsService);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
