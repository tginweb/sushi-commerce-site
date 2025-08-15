<?php

$commonConfig = include('public.php');

$envConfig = [
    'DEBUG' => [
        'ENABLE' => true
    ],
    'GRAPHQL' => [
        'CACHE_ENABLE' => true
    ],
];


return array_replace_recursive($commonConfig, $envConfig);
