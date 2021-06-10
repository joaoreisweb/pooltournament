<?php
date_default_timezone_set('Europe/Lisbon');

require_once '../vendor/autoload.php';
require_once '../includes/MySQLDriver.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

