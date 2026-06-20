<?php
include "../config/db_conn.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM blog WHERE id=$id");

header("Location: bloglist.php");
?>
