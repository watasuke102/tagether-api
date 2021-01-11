<?php
# API for TAGether
# request.php
#
# CopyRight (c) 2020 Watasuke
# Email  : <watasuke102@gmail.com>
# Twitter: @Watasuke102
# This software is released under the MIT SUSHI-WARE License.
require_once __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

# headers
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Origin, Content-Type');
header('Access-Control-Allow-Origin: http://localhost:3000');

# Function
function error($mes) {
  http_response_code(400);
  $array['status']  = 'error';
  $array['message'] = $mes;
  print json_encode($array);
  exit(1);
}

$mysqli = new mysqli('localhost', $_ENV['SQL_USER'], $_ENV['SQL_PASS'], 'tagether');
if (mysqli_connect_error()) {
  error($mysqli->connect_error);
}

# POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  error('not POST');
}
$query  = 'INSERT INTO request (body) values ("' . $_GET['body'] . '")';
$result = $mysqli->query($query);
http_response_code(200);
$array['status'] = 'ok';
print json_encode($array);
return;
