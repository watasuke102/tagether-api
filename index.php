<?php
# API for TAGether
#
# CopyRight (c) 2020 Watasuke
# Email  : <watasuke102@gmail.com>
# Twitter: @Watasuke102
# This software is released under the MIT SUSHI-WARE License.

# Function
function error($mes) {
  http_response_code(400);
  $array['error'] = $mes;
  print json_encode($array);
  exit(1);
}

header('Content-Type: application/json; charset=UTF-8');
$mysqli = new mysqli('localhost', 'root', '', 'tagether');
if (mysqli_connect_error()) {
  error($mysqli->connect_error);
}

# POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $query   = 'INSERT INTO exam (title, description, tag, list) values ';
  var_dump(file_get_contents('php://input'));
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

# GET
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
