<?php

return [
    'biteship' => [
        'code'         => 'biteship',
        'title'        => 'Biteship',
        'description'  => 'Biteship Shipping Gateway',
        'active'       => true,
        'default_rate' => '0',
        'type'         => 'per_unit',
        'class'        => 'Fashion\Biteship\Carriers\Biteship',
    ],
];
