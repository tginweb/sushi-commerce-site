<?php

$commonConfig = include('common.php');

$envConfig = [
    'GRAPHQL' => [
        'CACHE_ENABLE' => false
    ],
    'PAGE' => [
        'IBLOCK_ID' => 67,
    ],
];

return array_replace_recursive($commonConfig, $envConfig);
