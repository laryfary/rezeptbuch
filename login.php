<?php

$path_up = "../";

require_once($path_up."engine/helper.php");

if (isset($_POST["email"]) and isset($_POST["password"])) {

$sql = "SELECT * FROM myTable WHERE email='".$dblink->real_escape_string($_POST["email"])."'";

$result = $dblink->query($sql);

if ($result->num_rows == 1) {

$user = mysqli_fetch_object($result);

$_SESSION["name"] = $user->firstname." ".$user->lastname;

header("Location: /rezeptbuch/dashboard");
}
else {

header("Location: /rezeptbuch/?error=unknown");
}
}

?>