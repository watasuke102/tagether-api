<?php
# API for TAGether
# index.php
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
header('Access-Control-Allow-Methods: POST, PUT, GET');
header('Access-Control-Allow-Origin: https://tagether.watasuke.tk');

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

####################
## POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $query   = 'INSERT INTO exam (title, description, tag, list) values ';
  $request = json_decode(file_get_contents('php://input'), true);
  if (is_null($request)) {
    error('json parse failed');
  }
  $list    = str_replace('"', '\\"', $request['list']);
  $query  .= '(' .
    '"' . $request['title'] . '",' .
    '"' . $request['desc']  . '",' .
    '"' . $request['tag']   . '",' .
    '"' . $list             . '")' ;
  $result = $mysqli->query($query);
  http_response_code(200);
  $array['status'] = 'ok';
  print json_encode($array);
  return;
}

####################
## PUT
else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  $query   = 'UPDATE exam SET ';
  $request = json_decode(file_get_contents('php://input'), true);
  if (is_null($request)) {
    error('json parse failed');
  }
  $list    = str_replace('"', '\\"', $request['list']);
  $query  .=
  'title="'       . $request['title'] . '",' .
  'description="' . $request['desc']  . '",' .
  'tag="'         . $request['tag']   . '",' .
  'list="'        . $list             . '" ' .
  'WHERE id='     . $request['id'];
  $result = $mysqli->query($query);
  http_response_code(200);
  $array['status'] = 'ok';
  print json_encode($array);
  return;
}

####################
## GET
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $query = 'SELECT * FROM exam';
  if (isset($_GET['id'])) {
    if(!preg_match('/[^0-9]/', $_GET['id'])) {
      $query .= ' WHERE id=' . $_GET['id'];
    } else {
      error('invalid id');
    }
  }

  $result = $mysqli->query($query);
  if ($result) {
    $i = 0;
    while ($row = $result->fetch_assoc()) {
      $array[$i]['id']         = (int)$row['id'];
      $array[$i]['updated_at'] = $row['updated_at'];
      $array[$i]['title']      = $row['title'];
      $array[$i]['desc']       = $row['description'];
      $array[$i]['tag']        = $row['tag'];
      $array[$i]['list']       = $row['list'];
      $i++;
    }
    $result->close();
    if (is_null($array)) {
      error('id not found');
    } else {
      http_response_code(200);
      print json_encode($array);
    }
  } else {
    error($mysqli->connect_error);
  }
  $mysqli->close();
}
