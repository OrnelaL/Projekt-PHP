<?php include "includes/header.php";
include "includes/navbar.php";
include "config/db_conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $conn->real_escape_string($_POST["name"]);
    $surname = $conn->real_escape_string($_POST["surname"]);
    $email   = $conn->real_escape_string($_POST["email"]);
    $phone   = $conn->real_escape_string($_POST["phone"]);
    $subject = $conn->real_escape_string($_POST["subject"]);
    $message = $conn->real_escape_string($_POST["message"]);

    $sql = "INSERT INTO messages (name, surname, email, phone, subject, message) 
            VALUES ('$name', '$surname', '$email', '$phone', '$subject', '$message')";

    if ($conn->query($sql)) {
        header("Location: contact.php?success=1");
    } else {
        header("Location: contact.php?error=1");
    }
    exit();
}
?>
<!-- Top section -->
<section class="top-section1">
    <div class="container">
        <h4 class="top-title text-center my-auto text-white">Contact Us</h4>
    </div>
</section>

<section class="sector">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="contact-card text-center">
                    <div class="wrapper">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h4>Location:</h4>
                    <h6>8R56+H6 Tirane, Albania</h6>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="contact-card text-center mt-3" style="background-color:#395902;">
                    <div class="wrapper" style="background-color: white;">
                        <i class="fa-solid fa-phone" style="color: #f2a222;"></i>
                    </div>
                    <h4 class="text-white">Phone:</h4>
                    <h6 class="text-white">1234567890</h6>
                </div>
            </div>


            <div class="col-lg-4">
                <div class="contact-card text-center">
                    <div class="wrapper">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <h4>Email:</h4>
                    <h6>helloworld@gmail.com</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Forms -->
<section class="sector">
    <div class="container">
        <div class="row align-content-center">
            <div class="col-lg-6">
                <h5 class="detalis">Send us a message</h5>
                <h3 class="title">Don't hesitate to contact us for more infomations.</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit sequi ex libero optio! Sunt,
                    consectetur dolor cumque consequatur asperiores voluptatibus sapiente? Voluptatum dolorem soluta
                    hic laborum impedit sit quos? Autem!</p>

                <hr>
            </div>

            <div class="col-lg-6">
                <form method="POST" class="contact-form">
                    <div class="row">
                        <?php
                        if (isset($_GET["success"])): ?>
                            <div class="alert alert-success text-center my-4" id="successMsg">Message sent successfully!</div>
                            <script>
                                setTimeout(function() {
                                    var msg = document.getElementById("successMsg");
                                    if (msg) {
                                        msg.style.transition = "opacity 0.8s ease";
                                        msg.style.opacity = "0";
                                        setTimeout(function() {
                                            msg.remove();
                                        }, 800);
                                    }
                                }, 3000);
                            </script>
                        <?php endif;
                        ?>
                        <div class="col-lg-6 py-2">
                            <!-- <label id="name">Name</label><br> -->
                            <input type="text" id="name" name="name" placeholder="Name">
                        </div>

                        <div class="col-lg-6 py-2">
                            <!-- <label id="surname">Surname</label><br> -->
                            <input type="text" id="surname" name="surname" placeholder="Surname">
                        </div>

                        <div class="col-lg-6 py-2">
                            <!-- <label id="email">Email</label><br> -->
                            <input type="email" id="email" name="email" placeholder="Email">
                        </div>

                        <div class="col-lg-6 py-2">
                            <!-- <label id="phone">Phone</label><br> -->
                            <input type="tel" id="phone" name="phone" placeholder="Phone">
                        </div>

                        <div class="col-lg-12 py-2">
                           
                            <input type="text" id="subject" name="subject" placeholder="Subject">
                        </div>

                        <div class="col-lg-12 py-2">
                            <textarea id="message" name="message" placeholder="Message"></textarea>
                        </div>
                        <div class="col-lg-12 py-2">
                            <button type="submit" class="btn buy-btn w-100">Send a message</button>


                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Map -->
<section>
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2996.995258024165!2d19.80799517587447!3d41.30896677130988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x135030f76fce58ed%3A0x336baff0adbe4d1e!2sParku%20Zoologjik%20i%20Tiran%C3%ABs!5e0!3m2!1sen!2s!4v1769343255709!5m2!1sen!2s"
        style="width:100%; height:400px;" class="py-0" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>
<?php include "includes/footer.php" ?>