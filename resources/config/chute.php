<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Primary location to store files
    |
    */
    'storage'  => [
        'temp_disk'    => env('IMAGE_ENABLE_TEMP_DISK', 'temp'),
        'primary_disk' => env('IMAGE_ENABLE_PRIMARY_DISK', 'public'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Defaults
    |--------------------------------------------------------------------------
    |
    | Default rules for uploads and transformations if they are not set.
    | (Be sure that you only set default sizes and rules to those that exist)
    |
    */
    'defaults' => [
        'path'  => 'images',
        'sizes' => ['small', 'medium', 'large'],
        'rules' => 'standard',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    |
    | Rule definitions for valid files
    |
    */
    'rules'    =>
        [
            'standard'  =>
                [
                    'min_width'  => 400,
                    'min_height' => 400,
                    'max_width'  => null,
                    'max_height' => null,
                    'mime_types' => ['image/jpeg', 'image/png'],
                ],
            'animation' =>
                [
                    'min_width'  => 150,
                    'min_height' => 150,
                    'max_width'  => null,
                    'max_height' => null,
                    'mime_types' => ['image/gif'],
                ],

        ],

    /*
    |--------------------------------------------------------------------------
    | Sizes (Transformations)
    |--------------------------------------------------------------------------
    |
    | File size transformation definitions.
    |
    */
    'sizes'    =>
        [
            'small'  =>
                [
                    'prefix'          => '[small]',
                    'suffix'          => null,
                    'width'           => 200,
                    'height'          => null,
                    'quality'         => 90,
                    'placeholder_url' => 'http://via.placeholder.com/200x150/222/fff?text=Image+Processing',
                ],
            'medium' =>
                [
                    'prefix'          => '[medium]',
                    'suffix'          => null,
                    'width'           => 470,
                    'height'          => null,
                    'quality'         => 90,
                    'placeholder_url' => 'http://via.placeholder.com/470x246/222/fff?text=Image+Processing',
                ],
            'large' =>
                [
                    'prefix'          => '[large]',
                    'suffix'          => null,
                    'width'           => 675,
                    'height'          => 500,
                    'quality'         => 90,
                    'placeholder_url' => 'http://via.placeholder.com/800x600/222/fff?text=Image+Processing',
                ],
        ],
];