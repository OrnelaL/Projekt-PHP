<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="assets/image/logo.png" alt="logo" class="img-fluid nav-logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="animal.php">Animals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="blog.php">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="contact.php">Contact Us</a>
                </li>

                <?php if (isset($_SESSION["user"])): ?>
                    <!-- Perdoruesi eshtne i loguar -->
                    <!-- <li class="user-info">
                        <span class="nav-link px-3"> <?php echo htmlspecialchars($_SESSION["user"]); ?></span>
                    </li> -->
                    <?php if ($_SESSION["role"] === "user"): ?>
                    <li class="user-info">
                        <!-- <span class="nav-link px-3"> <?php echo htmlspecialchars($_SESSION["user"]); ?></span> -->
                          <a href="user.php" class="nav-link px-3"> <?php echo htmlspecialchars($_SESSION["user"]); ?></a>
                    </li>
                     <?php endif; ?>

                    <!-- Kontrollimi nqs eshte admin dhe e ben ridirect te admin/dashboard.php -->
                    <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                        <li>
                            <a href="admin/dashboard.php" class="nav-link px-3"> Admin</a>
                        </li>

                    <?php endif; ?>

                    <li class="nav-item">
                        <a href="logout.php" class="nav-link px-3">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- Logimi -->
                    <li class="nav-item">
                        <a href="login.php" class="nav-link px-3">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="register.php" class="nav-link px-3">Register</a>
                    </li>
                <?php endif; ?>

            </ul>
            <a href="tickets.php" class="ms-4 btn buy-btn">Buy a Tickets</a>
        </div>
    </div>
</nav>