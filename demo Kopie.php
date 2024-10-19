<?php

$dblink = mysqli_connect("localhost","root","","test");
 if($dblink->connect_errno) {
 	print "Verbindungsfehler";
  }
 	else {
 		print "Erfolgreich verbunden";
 }
?>