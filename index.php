<?php
header('Content-Type: application/json; charset=UTF-8');

# Function
function error($mes) {
  http_response_code(400);
  $array['error'] = $mes;
  print json_encode($array);
  exit(1);
}

# POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = json_decode(file_get_contents('php://input'), true);
  echo $request[0]['id'] . ' ';
  echo $request[1]['id'] . ' ';
  http_response_code(200);
  $array['status'] = 'ok';
  print json_encode($array);
  return;
}

# GET
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $mysqli = new mysqli('localhost', 'root', '', 'tagether');
  if (mysqli_connect_error()) {
    error($mysqli->connect_error);
  }

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
    if ($array == null) {
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
