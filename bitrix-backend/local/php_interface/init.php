<?php

global $request;

require_once __DIR__.'/../options.php';

$backendDir = $_SERVER['DOCUMENT_ROOT'] . '/local/' . BACKEND_FOLDER;

require_once $backendDir . '/vendor/autoload.php';

use Main\Request\AppScope;
use Main\Request\AppSetup;

$appScope = new AppScope($backendDir . '/config');
$appScope->loadFromRulesFile();

$appSetup = new AppSetup($appScope);
$appSetup->run();





