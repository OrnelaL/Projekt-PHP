<?php
include "config/db_conn.php";
include "includes/header.php";
include "includes/navbar.php";

$sql = "SELECT * FROM blog WHERE statusi='Publikuar' ORDER BY data_postimit DESC";
$result = mysqli_query($conn, $sql);
?>

<!-- Top section -->
<section class="top-section1">
    <div class="container">
        <h4 class="top-title text-center my-auto text-white">Events</h4>
    </div>
</section>

<section class="sector">
    <div class="container blog-sector">
        <div class="row gy-5">
            <h1 class="title text-center">Discover our events</h1>
            <p>Discover a world of excitement at our zoo events! From interactive animal encounters and educational workshops to seasonal celebrations and family-friendly activities, there’s something for everyone to enjoy. Come and make unforgettable memories while learning about wildlife and conservation.</p>
            <?php while ($blog = mysqli_fetch_assoc($result)) { ?>
                <div class="col-lg-4">
                    <div class="blog-card">
                        <h2><?= $blog['titulli']; ?></h2>
                        <p>
                            <small>
                                Author: <?= $blog['autori']; ?> |
                                Date: <?= date("d-m-Y", strtotime($blog['data_postimit'])); ?>
                            </small>
                        </p>
                        <p>
                            <?= substr($blog['permbajtja'], 0, 200); ?>...
                        </p>
                        <a href="blogpage.php?id=<?= $blog['id']; ?>" class="btn buy-btn">
                            Read More
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>


<?php
include "includes/footer.php";

?>