<?php

namespace SchulzeFelix\AdWords\Responses;

use SchulzeFelix\DataTransferObject\DataTransferObject;

class MonthlySearchVolume extends DataTransferObject
{
    protected $casts = [
        'year'  => 'integer',
        'month' => 'integer',
        'count' => 'integer',
    ];
}
