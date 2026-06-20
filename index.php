<?php include 'includes/header.php';
include "config/db_conn.php";
?>
<?php include 'includes/navbar.php';

$sql = "SELECT * FROM kafshet";
$result = mysqli_query($conn, $sql);
?>


<!-- Carousel -->
<div id="carouselExample" class="carousel slide">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="super-title">Discover the Wild Within</h2>
                        <p class="pb-4">Zoora can actively support global conservation efforts, <br>helping to
                            preserve threatened
                            species and their habitats.</p>
                        <a href="tickets.php" class="btn buy-btn">Buy a tickets</a>
                    </div>
                    <div class="col-lg-6">
                        <img src="assets/image/hero-sectionimg.png" class="img-fluid" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Us section -->
<section class="bg-sector about sector">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 my-0 py-0">
                <img src="assets/image/about1.jpg" class="img-fluid about1" alt="about image">
                <img src="assets/image/about2.jpg" class="img-fluid about2" alt="about image">
            </div>
            <div class="col-lg-6 ps-5 pt-5">
                <p class="detalis">Who we are</p>
                <h3 class="title">Connecting You to the<br> Wonders of Nature.</h3>
                <p>Welcome to our Zoo, a place where nature, wildlife, and education come together to inspire
                    curiosity and care for the animal world. Our zoo is home to a diverse range of species from
                    around the globe, providing visitors with a unique opportunity to explore and learn about
                    animals in a safe, welcoming, and natural environment.
                    <br>
                    We are committed to animal welfare, conservation, and environmental education. Our dedicated
                    team of professionals works tirelessly to ensure that every animal receives the highest standard
                    of care, while also creating meaningful experiences for our visitors. Through conservation
                    programs, educational activities, and interactive exhibits, we aim to raise awareness about the
                    importance of protecting wildlife and preserving natural habitats.
                </p>
                <a href="about.php" class="btn buy-btn">Discover More</a>
            </div>
        </div>
    </div>
</section>

<!-- Buy a tickets section -->
<section class="line-section d-flex">
    <h3 class="title text-white">Discover amazing animals, visit our zoo today!</h3>
    <a href="tickets.php" class="btn buy-btn">Buy a Ticket</a>
</section>

<!-- Mision -->
<section class="sector">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 vision d-flex flex-column justify-content-end">
                <h3 class="detalis">Our Vision</h3>
                <p class="text-white">Unlock the Secrets of the Animal Kingdom</p>
            </div>
            <div class="col-lg-6">
                <div class="row gy-4 ps-4">
                    <div class="col-lg-12 mision d-flex flex-column justify-content-end">
                        <h3 class="detalis">Our Mission</h3>
                        <p class="text-white">Our Zoo offers a safe and accessible way for people to see wildlife up
                            close.</p>
                    </div>
                    <div class="col-lg-12 motto d-flex flex-column justify-content-end">
                        <h3 class="detalis">Our Motto</h3>
                        <p class="text-white">Inspiring Future Generations to Protect Nature</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Animals -->
<section class="sector" style="background-color:#F2EFEB">
    <div class="container">
        <h5 class="detalis text-center">Animals</h5>
        <h3 class="title text-center">Up Close with Wildlife: Zoo Experiences.</h3>
        <p class="text-center">Explore the amazing world of our animals and get to know their unique stories and
            behaviors.</p>
        <div class="container blog-sector">
            <div class="row gy-5">

                <?php while ($kafshet = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-lg-4">
                        <div class="blog-card">
                            <h2><?= $kafshet['emri']; ?></h2>
                            <?php if (!empty($kafshet['imazh'])) { ?>
                                <img src="assets/uploads/<?= $kafshet['imazh']; ?>"
                                    style="width:300px; height:250px;" alt="<?= $kafshet['emri']; ?>" class="img-fluid picture">
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
<!-- Testimonials -->
<section class="sector">
    <div class="container">
        <h5 class="detalis text-center">Testimonials</h5>
        <h3 class="title text-center">Client Feedback & Reviews</h3>
        <div class="owl-carousel owl-theme testimonials-owl pt-5">
            <div class="item">
                <div class="testimonals text-center">
                    <h5>Lorem Ipsum</h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit obcaecati dolor ullam
                        autem facere, unde sapiente temporibus ad labore, qui nam corrupti ut hic reiciendis eos,
                        debitis ex beatae nihil?</p>
                    <p>Lorem Ipsum</p>
                </div>
            </div>
            <div class="item">
                <div class="testimonals text-center">
                    <h5>Lorem Ipsum</h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit obcaecati dolor
                        ullam
                        autem facere, unde sapiente temporibus ad labore, qui nam corrupti ut hic reiciendis
                        eos,
                        debitis ex beatae nihil?</p>
                    <p>Lorem Ipsum</p>
                </div>
            </div>
            <div class="item">
                <div class="testimonals text-center">
                    <h5>Lorem Ipsum</h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit obcaecati dolor
                        ullam
                        autem facere, unde sapiente temporibus ad labore, qui nam corrupti ut hic reiciendis
                        eos,
                        debitis ex beatae nihil?</p>
                    <p>Lorem Ipsum</p>
                </div>
            </div>
            <div class="item">
                <div class="testimonals text-center">
                    <h5>Lorem Ipsum</h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit obcaecati dolor
                        ullam
                        autem facere, unde sapiente temporibus ad labore, qui nam corrupti ut hic reiciendis
                        eos,
                        debitis ex beatae nihil?</p>
                    <p>Lorem Ipsum</p>
                </div>
            </div>
            <div class="item">
                <div class="testimonals text-center">
                    <h5>Lorem Ipsum</h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit obcaecati dolor
                        ullam
                        autem facere, unde sapiente temporibus ad labore, qui nam corrupti ut hic reiciendis
                        eos,
                        debitis ex beatae nihil?</p>
                    <p>Lorem Ipsum</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog -->
<?php
$result = mysqli_query($conn, "SELECT * FROM blog ORDER BY data_postimit DESC LIMIT 3");
?>
<section class="sector bg-sector">
    <div class="container">
        <div class="row">
            <h5 class="detalis text-center">Blogs</h5>
            <h3 class="title text-center">Article and News</h3>
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
<?php include 'includes/footer.php'; ?>