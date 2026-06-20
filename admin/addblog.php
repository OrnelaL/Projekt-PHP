<?php
session_start();
include "../config/db_conn.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../index.php");
    exit();
}

$user_name = $_SESSION['user'];

if (isset($_POST['shto'])) {

    $titulli = $_POST['titulli'];
    $permbajtja = $_POST['permbajtja'];
    $autori = $_POST['autori'];
    $imazh = $_POST['imazh'];

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../assets/uploads/" . $imageName
        );
    } else {
        $imageName = '';
    }

    $sql = "INSERT INTO blog (titulli, permbajtja, autori, imazh, statusi, data_postimit)
            VALUES ('$titulli', '$permbajtja', '$autori', '$imazh', 'Publikuar', NOW())";

    mysqli_query($conn, $sql);
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
            <a href="dashboard.php" class=" fw-bold logout-btn">Admin dashboard</a>
        </div>
    </div>
</div>
<section class="dashboard-part">
    <div class="container">
        <div class="row">

            <div class="col-lg-8 mx-auto py-5">
                <h2 class="text-white mt-5">Add new event </h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="text" name="titulli" placeholder="Title" required><br><br>
                    <textarea name="permbajtja" placeholder="Content"class="my-3"  required></textarea><br><br>
                    <input type="file" name="imazh" class="my-3" ><br><br>
                    <input type="text" name="autori" placeholder="Author" class="my-3" ><br><br>
                    <button name="shto" type="submit" class=" mx-auto d-block my-4 btn buy-btn">Add blog</button>
                </form>

            </div>
        </div>
    </div>
</section>