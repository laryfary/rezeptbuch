<?php
$mysqli = new mysqli("localhost", "root", "", "test"); if ($mysqli->connect_errno) {
echo $mysqli->connect_error; }
 ?>
