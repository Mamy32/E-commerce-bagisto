<?php

return [
    [
        'key'    => 'sales.carriers.biteship',
        'name'   => 'Biteship',
        'info'   => 'Biteship Shipping Gateway',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.carriers.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.carriers.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.carriers.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'environment',
                'title'         => 'Environment',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'Sandbox',
                        'value' => 'sandbox',
                    ],
                    [
                        'title' => 'Production',
                        'value' => 'production',
                    ],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'origin_area_id',
                'title'         => 'Origin Area ID (Biteship)',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
                'info'          => 'The Biteship area ID of your warehouse (e.g., IDNP12...)'
            ],
            [
                'name'          => 'active_couriers',
                'title'         => 'Active Couriers',
                'type'          => 'multiselect',
                'options'       => [
                    ['title' => 'JNE', 'value' => 'bs_jne'],
                    ['title' => 'Sicepat', 'value' => 'bs_sicepat'],
                    ['title' => 'JNT', 'value' => 'bs_jnt'],
                    ['title' => 'GoSend', 'value' => 'bs_gosend'],
                    ['title' => 'GrabExpress', 'value' => 'bs_grab'],
                    ['title' => 'Paxel', 'value' => 'bs_paxel'],
                    ['title' => 'Ninja Xpress', 'value' => 'bs_ninja'],
                    ['title' => 'Anteraja', 'value' => 'bs_anteraja'],
                    ['title' => 'Lion Parcel', 'value' => 'bs_lion'],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'default_weight',
                'title'         => 'Default Weight (grams)',
                'type'          => 'text',
                'validation'    => 'required|numeric',
                'channel_based' => true,
                'locale_based'  => false,
                'info'          => 'Used if product weight is not set'
            ],
        ],
    ],
];
