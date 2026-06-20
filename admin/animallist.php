<?php
session_start();
include "../config/db_conn.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../index.php");
    exit();
}

$user_name = $_SESSION['user'];

$result_3 = mysqli_query($conn, "SELECT * FROM kafshet");
?>

<head>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<div class="dashboard">
    <div class="container">
        <div class="top-admin d-flex justify-content-between align-items-center">
            <div class="">
                <h5>Admin Dashboard</h5>
                <h3>Welcome, <?php echo htmlspecialchars($user_name); ?></h3>
            </div>
            <a href="dashboard.php" class=" fw-bold logout-btn">Admin dashboard</a>
        </div>
    </div>
</div>

<section class="dashboard-part">
    <div class="container">
        <div class="row">

            <div class="col-lg-8 mx-auto py-5">
                <h2 class="text-white mt-5">Animal List</h2>
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>

                    <?php while ($row = mysqli_fetch_assoc($result_3)) { ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['emri'] ?></td>
                            <td><?= $row['vendorigjina'] ?></td>
                            <td>
                                <a href="editanimal.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a> |
                                <a href="deleteanimal.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-delete">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <br>
                <a href="addanimal.php" class="btn btn-add mb-5">Add new Animal</a>

            </div>
        </div>
    </div>
</section>