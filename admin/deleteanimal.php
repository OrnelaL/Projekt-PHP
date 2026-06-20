<?php
include "../config/db_conn.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM kafshet WHERE id=$id");

header("Location: animallist.php");
?>
