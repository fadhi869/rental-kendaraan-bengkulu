<?php
return [
    'name'     => getenv('APP_NAME') ?: 'Rental Armada Bengkulu',
    'env'      => getenv('APP_ENV') ?: 'local',
    'debug'    => getenv('APP_DEBUG') !== false ? filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN) : true,
    'timezone' => getenv('APP_TIMEZONE') ?: 'Asia/Jakarta',
    'base_url' => getenv('APP_URL') ?: '',
];
