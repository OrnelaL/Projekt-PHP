<?php
$host = "localhost";
$user = "root";
$pass = "";
$name = "zoo";

$conn = mysqli_connect($host, $user, $pass, $name);

if (!$conn) {
    die("Lidhja deshtoi: " . mysqli_connect_error());
}
