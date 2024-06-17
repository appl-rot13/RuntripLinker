<?php

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/runtrip.php';
require_once __DIR__ . '/twitter.php';

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
