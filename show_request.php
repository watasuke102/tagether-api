<!DOCTYPE HTML>
<html>
<head>
<style>
body {
  background-color: #151528;
  color: #dddddd;
}
th {
  border-bottom: 1px solid #d6d5a1;
}
td {
  padding: 5px 15px;
}
</style>
<title>要望一覧</title>
</head>
<body>
  <h1>要望一覧</h1>
  <table>
  <tr>
    <th>日時</th>
    <th>要望</th>
    <th>解答</th>
  </tr>
<?php
require_once __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mysqli = new mysqli('localhost', $_ENV['SQL_USER'], $_ENV['SQL_PASS'], $_ENV['SQL_DATABASE']);
if (mysqli_connect_error()) {
  print 'error: ' . $mysqli->connect_error;
}

$query = 'SELECT * FROM request';

$result = $mysqli->query($query);
if ($result) {
  $rows = $result->fetch_array();
  while ($row = $result->fetch_assoc()) {
    print '<tr>';
    print '<td>' . $row['updated_at']   . '</td>';
    print '<td>' . $row['body']   . '</td>';
    print '<td>' . $row['answer'] . '</td>';
    print '</tr>';
  }
} else {
  print 'error';
}
?>
</table>
</body>
</html>