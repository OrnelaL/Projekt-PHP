<?php
session_start();
include "../config/db_conn.php";

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM tickets WHERE id=$id");

header("Location: addtickets.php");
exit();
?>