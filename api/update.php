<?php

include_once('../core/init_api.php');

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$db = new MySQLDriver($_ENV['DB_SERVER'],$_ENV['DB_NAME'],$_ENV['DB_USER'],$_ENV['DB_PASSWORD']);
$db->db_connect();

//echo json_encode($db->read('pool_players'));

$table = isset($_POST['table'])?$_POST['table']:'';
$id = isset($_POST['id'])?$_POST['id']:'';
$data = isset($_POST['data'])?$_POST['data']:'';

if($table!='' && startsWith($table, 'pool_')){
    echo json_encode( $db->update($table, $id, $data ) );
}

/////// check tables prefiix
function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}