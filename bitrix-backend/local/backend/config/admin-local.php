<?php

$commonConfig = include('admin.php');

$envConfig = [
    'DEBUG' => [
        'ENABLE' => true
    ],
];


return array_replace_recursive($commonConfig, $envConfig);
