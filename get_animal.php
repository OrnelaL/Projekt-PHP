<?php
include "config/db_conn.php";

header('Content-Type: application/json');

// Siguro ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID invalid']);
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT data_ardhjes, pershkrimi, dieta FROM kafshet WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        'success'      => true,
        'data_ardhjes' => htmlspecialchars($row['data_ardhjes']),
        'pershkrimi'   => htmlspecialchars($row['pershkrimi']),
        'dieta'        => htmlspecialchars($row['dieta'])
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'This animal doesnot exist']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>