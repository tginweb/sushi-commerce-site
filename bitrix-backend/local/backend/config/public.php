<?php

$commonConfig = include('common.php');


$envConfig = [

    'GRAPHQL' => [
        'CACHE_ENABLE' => true
    ],
];


return array_replace_recursive($commonConfig, $envConfig);
