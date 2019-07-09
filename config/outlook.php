<?php

return [
    // On the initial Outlook email sync, the number of days to go back to fetch emails.
    'days_back' => 5,
    'ignore' => 'CONFIDENTIALITY NOTICE: This e-mail and any attachments are for the exclusive and confidential use of intended recipient. If you are not the intended recipient, please do not read, distribute or take action in reliance to this message. If you have received this in error, please notify us immediately by return e-mail and promptly delete this message and its attachments from your computer system.',
    'categories' => [
        'vsf' => [
            'from_address' => 'gsanders@varsity.com',
            'subject' => [
                'vsf',
                'sublimation',
                'request',
            ],
            'body' => [
                'vsf',
                'sublimation',
                'garment',
            ],
        ],
        'swatch' => [
            'subject' => [
                'swatch', 'swatches', 'sample', 'samples'
            ],
            'body' => [
                'swatches',
                'swatch',
            ],
        ],
        'prototype' => [
            'from_address' => 'mcuenca@varsity.com',
            'body' => [
                'new files by',
                'new file by',
                'new style by',
                'voucher by',
                'new pattern',
                'new styles by',
                'vouchers by',
                'delete old',
                'old files',
                'new patterns',
                'delete the old',
            ],
        ],
        'ozone' => [
            'from_address' => 'bmccollum@varsity.com',
            'subject' => [
                'ozone'
            ],
            'body' => [
                'on the server',
                'server now',
            ],
        ],
        'lettering' => [
            'from_address' => 'nostlund@varsity.com',
            'subject' => [
                'lettering holding'
            ],
        ],
    ],
];
