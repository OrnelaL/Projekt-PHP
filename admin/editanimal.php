<?php
session_start();
include "../config/db_conn.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../index.php");
    exit();
}

$user_name = $_SESSION['user'];
$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM kafshet WHERE id=$id");
$animal = mysqli_fetch_assoc($result);

if (isset($_POST['update_animal'])) {
    $emri = $_POST['emri'];
    $lloji = $_POST['lloji'];
    $gjinia = $_POST['gjinia'];
    $mosha = $_POST['mosha'];
    $vendorigjina = $_POST['vendorigjina'];
    $dieta = $_POST['dieta'];
    $data_ardhjes = $_POST['data_ardhjes'];
    $pershkrimi = $_POST['pershkrimi'];

    if (!empty($_FILES['upload_image']['name'])) {
        $imageName = time() . "_" . $_FILES['upload_image']['name'];
        move_uploaded_file(
            $_FILES['upload_image']['tmp_name'],
            "../assets/uploads/" . $imageName
        );
    } else {
        $imageName = $_POST['old_image'];
    }

    mysqli_query($conn, "UPDATE kafshet SET
        emri='$emri',
        lloji='$lloji',
        gjinia='$gjinia',
        imazh='$imageName',
        mosha='$mosha',
        vendorigjina='$vendorigjina',
        dieta='$dieta',
        data_ardhjes='$data_ardhjes',
        pershkrimi='$pershkrimi'
        WHERE id=$id
    ");

    header("Location: animallist.php");
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
                    <h2 class="text-white">Edit animal's information</h2>
                    <a href="animallist.php" class=" fw-bold logout-btn">Animal List</a>
                </div>
                <form method="POST" enctype="multipart/form-data">

                    <input type="text" name="emri" value="<?= $animal['emri'] ?>" required><br><br>

                    <input type="text" name="lloji" class="my-3" value="<?= $animal['lloji'] ?>" required><br><br>

                    <label>Gender:</label><br>
                   <div class="d-flex" style="gap:20px">
                    <label><input type="radio" name="gjinia" value="Mashkull" <?= $animal['gjinia'] == 'Mashkull' ? 'checked' : '' ?>> Male</label>
                    <label><input type="radio" name="gjinia" value="Femer" <?= $animal['gjinia'] == 'Femer' ? 'checked' : '' ?>> Female</label>
                   </div>
                    <br><br>

                    <input type="number" name="mosha" class="my-3"  value="<?= $animal['mosha'] ?>"><br><br>

                    <input type="text" name="vendorigjina" class="my-3"  value="<?= $animal['vendorigjina'] ?>" required><br><br>

                    <textarea name="dieta" class="my-3"  required><?= $animal['dieta'] ?></textarea><br><br>

                    <input type="date" class="my-3"  name="data_ardhjes" value="<?= $animal['data_ardhjes'] ?>"><br><br>

                    <textarea name="pershkrimi"><?= $animal['pershkrimi'] ?></textarea><br><br>

                    <img src="../assets/uploads/<?= $animal['imazh'] ?>" class="my-3"  width="150"><br><br>

                    <input type="hidden" name="old_image" value="<?= $animal['imazh'] ?>">
                    <input type="file" name="upload_image"><br><br>

                    <button type="submit" class="btn buy-btn my-4 mx-auto d-block" name="update_animal">Update animal</button>

                </form>


            </div>
        </div>
    </div>
</section>