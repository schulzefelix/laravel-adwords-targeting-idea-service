<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AdWords Developer Token
    |--------------------------------------------------------------------------
    */
    'developer_token' => env('ADWORDS_DEVELOPER_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | OAUTH2 Credentials
    |--------------------------------------------------------------------------
    */

    'client_id'            => env('ADWORDS_CLIENT_ID'),
    'client_secret'        => env('ADWORDS_CLIENT_SECRET'),
    'client_refresh_token' => env('ADWORDS_CLIENT_REFRESH_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Client Customer ID
    |--------------------------------------------------------------------------
    */

    'client_customer_id' => env('ADWORDS_CLIENT_CUSTOMER_ID'),

    /*
    |--------------------------------------------------------------------------
    | User Agent
    |--------------------------------------------------------------------------
    */

    'user_agent' => env('ADWORDS_USER_AGENT', ''),

    /*
    |--------------------------------------------------------------------------
    | Logging - Path
    |--------------------------------------------------------------------------
    |
    | Supported:
    |
    | null (Console Output stderr)
    | storage_path('logs/soap.log')
    |
    */

    'soap_log_file_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Logging - Level
    |--------------------------------------------------------------------------
    |
    | http://www.php-fig.org/psr/psr-3/#psrlogloglevel
    | Default: ERROR
    |
    */

    'soap_log_level' => env('ADWORDS_SOAP_LOG_LEVEL', 'ERROR'),
];
