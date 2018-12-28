<?php
return [
    'adminEmail'                    => 'admin@example.com',
    'supportEmail'                  => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordResetCodeExpire'  => 60 * 60 * 24, // сутки, срок жизни токена
    // http://smsc.kz
    'sms'                           => [
        'client' => [
            'url'       => 'http://smsc.kz/sys/soap.php?wsdl',
            'login'     => '',
            'password'  => '',
            'sender'    => 'SMS-INFO',
            'test_mode' => false,
        ],
    ],
];
