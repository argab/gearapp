<?php

return [
    '' => 'admin/index',
    '<_a:about|contact>' => 'admin/<_a>',
    '<_a:signup|login|logout>' => 'auth/<_a>',

    '<_c:[\w\-]+>' => '<_c>/index',
    '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
    '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
    '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
];

