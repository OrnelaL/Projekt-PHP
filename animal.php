<?php
include "config/db_conn.php";
include "includes/header.php";
include "includes/navbar.php";

$sql = "SELECT * FROM kafshet";
$result = mysqli_query($conn, $sql);
?>

<section class="top-section1">
    <div class="container">
        <h4 class="top-title text-center my-auto text-white">Animals</h4>
    </div>
</section>

<section class="sector">
    <div class="container blog-sector">
        <div class="row gy-5">
            <h1 class="title text-center">Our Animals Test</h1>
            <?php while ($kafshet = mysqli_fetch_assoc($result)) { ?>
                <div class="col-lg-4">
                    <div class="blog-card">
                        <h2><?= $kafshet['emri']; ?></h2>
                        <?php if (!empty($kafshet['imazh'])) { ?>
                            <img src="assets/uploads/<?= $kafshet['imazh']; ?>"
                                style="width:300px;" alt="<?= $kafshet['emri']; ?>" class="img-fluid picture">
                        <?php } ?>

                        <!-- INFO E KUFIZUAR -->
                        <p class="my-3">
                            <small>
                                Type: <?= $kafshet['lloji']; ?> |
                                Origin Place: <?= $kafshet['vendorigjina']; ?>
                            </small>
                        </p>


                        <!-- INFO E PLOTe (e fshehur) -->
                        <p><?= substr($kafshet['pershkrimi'], 0, 100); ?>...</p>
                        <div id="extra-<?= $kafshet['id']; ?>" style="display:none;"></div>

                        <button class="btn buy-btn btn-sm mb-2 show-more-btn"
                            data-id="<?= $kafshet['id']; ?>">
                            Read More
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<script>
    document.querySelectorAll('.show-more-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const extraDiv = document.getElementById('extra-' + id);
            const btnEl = this;

            if (extraDiv.style.display === 'block') {
                extraDiv.style.display = 'none';
                btnEl.textContent = 'Read more';
                return;
            }

            if (extraDiv.innerHTML.trim() !== '') {
                extraDiv.style.display = 'block';
                btnEl.textContent = 'Read less ▲';
                return;
            }

            btnEl.textContent = 'Loadding...';

            fetch('get_animal.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        extraDiv.innerHTML = `
                        <hr>
                        <p><strong>Arrival Date:</strong> ${data.data_ardhjes}</p>
                        <p><strong>Description:</strong> ${data.pershkrimi}</p>
                        <p><strong>Diet:</strong> ${data.dieta}</p>
                    `;
                        extraDiv.style.display = 'block';
                        btnEl.textContent = 'Read more';
                    } else {
                        btnEl.textContent = 'Read less';
                    }
                })
                .catch(() => {
                    btnEl.textContent = 'Read More';
                });
        });
    });
</script>

<?php include "includes/footer.php"; ?>