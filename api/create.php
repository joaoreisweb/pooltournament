<?php

include_once('../core/init_api.php');

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$db = new MySQLDriver($_ENV['DB_SERVER'],$_ENV['DB_NAME'],$_ENV['DB_USER'],$_ENV['DB_PASSWORD']);
$db->db_connect();
