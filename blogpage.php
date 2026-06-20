<?php
include "config/db_conn.php";
include "includes/header.php";
include "includes/navbar.php";

$sql = "SELECT * FROM blog WHERE statusi='Publikuar' ORDER BY data_postimit DESC";
$result2 = mysqli_query($conn, $sql);


if (!isset($_GET['id'])) {
    header("Location: blog.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM blog WHERE id=$id";
$result = mysqli_query($conn, $sql);
$blog = mysqli_fetch_assoc($result);

if (!$blog) {
    echo "Blogu nuk u gjet!";
    exit;
}
?>

<head>
    <title><?= $blog['titulli']; ?></title>

<body>
    <!-- Top section -->
    <section class="top-section1">
        <div class="container">
            <h4 class="top-title text-center my-auto text-white"><?= $blog['titulli'] ?></h4>
        </div>
    </section>
    <section class="sector container">
        <div class=" blog">
            <div class="blog-part1">
                <h1 class="title"><?= $blog['titulli']; ?></h1>
                <?php if (!empty($blog['imazh'])) { ?>
                    <img src="assets/uploads/<?= $blog['imazh']; ?>" alt="<?= $blog['titulli']; ?>" class="img-fluid picture">
                <?php } ?>
                <br><br>
                <p>
                    Autori: <?= $blog['autori']; ?> |
                    Data: <?= date("d-m-Y", strtotime($blog['data_postimit'])); ?>
                </p>
                <hr>

                <p><?= nl2br($blog['permbajtja']); ?></p>

                <br>
                <a href="blog.php" class="btn buy-btn">Go Back</a>

            </div>


            <div class="blog-part2">
                <h4 class="title">
                    Stay update width our lastet events
                </h4>
                <div class="row">
                    <div class="col-lg-12">
                        <?php while ($blog = mysqli_fetch_assoc($result2)) { ?>
                            <div class="blog-card">
                                <p><?= $blog['autori'] ?></p>
                                <p><?= $blog['titulli'] ?></p>
                                <hr>
                                <a href="blogpage.php?id=<?= $blog['id'] ?>" class="read">Read more</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    include "includes/footer.php";

    ?>