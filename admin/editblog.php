<?php
session_start();
include "../config/db_conn.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../index.php");
    exit();
}

$user_name = $_SESSION['user'];

$id = $_GET['id'];
$blog = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blog WHERE id=$id"));

if (isset($_POST['update'])) {
    $titulli = $_POST['titulli'];
    $permbajtja = $_POST['permbajtja'];
    $autori = $_POST['autori'];

    if (!empty($_FILES['upload_image']['name'])) {
        $imageName = time() . "_" . $_FILES['upload_image']['name'];
        $tmpName = $_FILES['upload_image']['tmp_name'];
        move_uploaded_file($tmpName, "../assets/uploads/" . $imageName);
    } else {
        $imageName = $_POST['old_image'];
    }

    mysqli_query($conn, "UPDATE blog SET 
        titulli='$titulli',
        permbajtja='$permbajtja',
        imazh='$imageName',
        autori='$autori'
        WHERE id=$id
    ");

    header("Location: bloglist.php");
    exit;
}
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
            <a href="dashboard.php" class=" fw-bold logout-btn">Admin Dashboard</a>
        </div>
    </div>
</div>
<section class="dashboard-part">
    <div class="container">
        <div class="row">

            <div class="col-lg-8 mx-auto">
                <div class="d-flex justify-content-between align-items-center py-5">
                    <h2 class="text-white">Edit event's information</h2>
                    <a href="bloglist.php" class=" fw-bold logout-btn">Event's List</a>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="text" name="titulli" value="<?= $blog['titulli'] ?>"><br><br>
                    <textarea name="permbajtja" class="my-3" style="height:300px;"><?= $blog['permbajtja'] ?></textarea><br><br>
                    <img src="assets/uploads/<?= $blog['imazh']; ?>" class="my-3" width="150"><br><br>
                    <input type="file" name="upload_image"><br><br>
                    <input type="text" class="my-3" name="autori" value="<?= $blog['autori'] ?>"><br><br>
                    <button name="update" class="btn buy-btn mx-auto d-block">Save Changes</button>
                </form>

            </div>
        </div>
    </div>
</section>