<?php
include "config/db_conn.php";
include 'includes/header.php';
include 'includes/navbar.php';

// Marrim te gjitha biletat nga databaza
$result = $conn->query("SELECT * FROM tickets ORDER BY id ASC");
$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}

$message = "";

if (isset($_POST["book"])) {
    if (!isset($_SESSION["user"])) {
        header("Location: login.php");
        exit;
    } else {
        $ticketName = htmlspecialchars($_POST["ticket"]);
        $price = htmlspecialchars($_POST["price"]);

        $user = $_SESSION["user"];
        $conn->query("INSERT INTO bookings (username, ticket_name, price) VALUES ('$user', '$ticketName', $price)");

        $message = "✅ Successful reservation for: <b>$ticketName</b> (€$price)";
    }
}

$conn->close();
?>

<!-- Top section -->
<section class="top-section1">
    <div class="container">
        <h4 class="top-title text-center my-auto text-white">Tickets</h4>
    </div>
</section>
<section class="sector body-admin">
    <div class="container ">

        <h1 class="title text-center py-5">Discover our Zoo Tickets</h1>
        <div class="row">
            <?php if (count($tickets) > 0): ?>
                <?php foreach ($tickets as $t): ?>
                    <div class="col-lg-4">
                        <div class="card-ticket">
                            <div class="card-icon"><?php echo $t["icon"]; ?></div>
                            <h3><?php echo htmlspecialchars($t["name"]); ?></h3>
                            <p class="description"><?php echo htmlspecialchars($t["description"]); ?></p>
                            <div class="title text-center">€<?php echo number_format($t["price"], 2); ?></div>

                            <div class="features">
                                <ul>
                                    <li><?php echo htmlspecialchars($t["feature1"]); ?></li>
                                    <li><?php echo htmlspecialchars($t["feature2"]); ?></li>
                                    <li><?php echo htmlspecialchars($t["feature3"]); ?></li>
                                </ul>
                            </div>

                            <form method="post">
                                <input type="hidden" name="ticket" value="<?php echo htmlspecialchars($t["name"]); ?>">
                                <input type="hidden" name="price" value="<?php echo $t["price"]; ?>">
                                <a href="booking.php?ticket_id=<?php echo $t['id']; ?>" class="btn buy-btn">
                                    Book Now
                                </a>


                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-tickets">
            <p>There are no tickets available at the moment.</p>
            <p style="margin-top: 10px; font-size: 0.9em;">Please try again later.</p>
        </div>
    <?php endif; ?>
    </div>
    <?php if ($message): ?>
        <div class="msg"><?php echo $message; ?></div>
    <?php endif; ?>


    </div>
</section>
<?php include "includes/footer.php" ?>