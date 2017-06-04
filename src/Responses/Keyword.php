<?php

namespace SchulzeFelix\AdWords\Responses;

use SchulzeFelix\DataTransferObject\DataTransferObject;

class Keyword extends DataTransferObject
{
    protected $casts = [
        'keyword'                   => 'string',
        'search_volume'             => 'integer',
        'cpc'                       => 'float',
        'competition'               => 'float',
        'targeted_monthly_searches' => 'collection',
    ];
}
