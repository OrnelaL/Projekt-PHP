<?php
session_start();
include "../config/db_conn.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../index.php");
    exit();
}

$user_name = $_SESSION['user'];

if (isset($_POST['add_animal'])) {

    $emri = $_POST['emri'];
    $lloji = $_POST['lloji'];
    $gjinia = $_POST['gjinia'];
    $mosha = $_POST['mosha'];
    $vendorigjina = $_POST['vendorigjina'];
    $dieta = $_POST['dieta'];
    $data_ardhjes = $_POST['data_ardhjes'];
    $pershkrimi = $_POST['pershkrimi'];

    // IMAGE
    if (!empty($_FILES['imazh']['name'])) {
        $imageName = time() . "_" . $_FILES['imazh']['name'];
        move_uploaded_file(
            $_FILES['imazh']['tmp_name'],
            "../assets/uploads/" . $imageName
        );
    } else {
        $imageName = '';
    }

    $sql = "INSERT INTO kafshet
    (emri, lloji, gjinia, imazh, mosha, vendorigjina, dieta, data_ardhjes, pershkrimi)
    VALUES
    ('$emri', '$lloji', '$gjinia', '$imageName', '$mosha', '$vendorigjina', '$dieta', '$data_ardhjes', '$pershkrimi')";

    mysqli_query($conn, $sql);
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
            <a href="dashboard.php" class=" fw-bold logout-btn">Admin dashboard</a>
        </div>
    </div>
</div>
<section class="dashboard-part">
    <div class="container">
        <div class="row">
           
            <div class="col-lg-8 mx-auto py-5">
                 <h2 class="text-white">Add new animal</h2>
                <form method="POST" enctype="multipart/form-data">

                    <input type="text" name="emri" placeholder="Name" required><br><br>

                    <input type="text" name="lloji" placeholder="Type"  class="my-3" required><br><br>

                    <label>Gjinia:</label><br>
                    <div class="d-flex" style="gap:20px;">
                    <label class="text-white"><input type="radio" name="gjinia" value="Mashkull" required> Male</label>
                   <label class="text-white"> <input type="radio" name="gjinia" value="Femer" class="text-white"> Female</label>
                    </div>
                    <br><br>

                    <input type="number" name="mosha" placeholder="Age" class="my-3"  required><br><br>

                    <input type="text" name="vendorigjina" placeholder="Origin Place"  class="my-3"  required><br><br>

                    <label>Diet:</label><br>
                    <textarea name="dieta" required></textarea><br><br>

                    <label>Arrival date:</label><br>
                    <input type="date" name="data_ardhjes" required><br><br>

                    <label>Description:</label><br>
                    <textarea name="pershkrimi"></textarea><br><br>

                    <label>Image:</label><br>
                    <input type="file" name="imazh" required><br><br>

                    <button type="submit" name="add_animal" class=" mx-auto d-block my-4 btn buy-btn">Add animal</button>

                </form>
            </div>
        </div>
    </div>
</section>