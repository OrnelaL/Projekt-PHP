<?php
session_start();
include 'includes/header.php';
require_once "config/db_conn.php";

// Kontrollo nese ka user te loguar
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Kontrollo nese useri eshte admin
$user_role = $_SESSION['role'] ?? '';
if ($user_role !== "admin" && !isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Te dhenat e perdoruesit
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user'];
$user_email = $_SESSION['email'];

// Statistikat
$total_tickets = $conn->query("SELECT COUNT(*) as count FROM tickets")->fetch_assoc()['count'];

// Rezervimet
if ($user_role === 'admin') {
    // Admin sheh te gjitha rezervimet
    $bookings_query = $conn->query("SELECT * FROM bookings ORDER BY booking_date DESC LIMIT 50");
    $total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
} else {
    // User normal sheh vetem rezervimet e veta
    $bookings_query = $conn->query("SELECT * FROM bookings WHERE username = '{$_SESSION['user']}' ORDER BY booking_date DESC LIMIT 10");
    $total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE username = '{$_SESSION['user']}'")->fetch_assoc()['count'];
}

?>

<main class="user-dashboard bg-sector">
    <div class="dashboard-container">
        <section class="welcome-section user-welcome">
            <div>
                <p class="detalis user-kicker">My Zoo Account</p>
                <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
                <p><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($user_email); ?></p>
            </div>
            <span class="user-badge">
                <i class="fa-solid fa-user"></i>
                <?php echo $user_role === 'admin' ? 'Admin' : 'User'; ?>
            </span>
        </section>

        <section class="user-stats row g-4">
            <div class="col-lg-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-ticket"></i></div>
                    <div class="stat-number"><?php echo $total_bookings; ?></div>
                    <div class="stat-label">Your bookings</div>
                    <span class="stat-card-spacer"></span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-calendar" style="color:#155724;"></i></div>

                    <div class="stat-label">See the events organized at the zoo.</div>
                    <br>
                    <a href="tickets.php" class="btn buy-btn">Events</a>
                </div>


            </div>
            <div class="col-lg-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-hippo" style="color:#ff9800;"></i></div>

                    <div class="stat-label">The animals that are in the zoo.</div>
                    <br>
                    <a href="animal.php" class="btn buy-btn">Animals</a>
                </div>

            </div>
        </section>

        <section class="quick-actions">
            <div class="user-section-heading">
                <p class="detalis">Quick Actions</p>
                <h2 class="title">Plan your next visit</h2>
            </div>
            <div class="action-buttons">
                <a href="index.php" class="action-btn"><i class="fa-solid fa-house"></i> Home</a>
                <a href="tickets.php" class="action-btn"><i class="fa-solid fa-ticket"></i> Tickets</a>
                <?php if ($user_role === 'admin'): ?>
                    <a href="admin/dashboard.php" class="action-btn"><i class="fa-solid fa-screwdriver-wrench"></i> Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php" class="action-btn logout-action"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </section>

        <section class="booking-section">
            <div class="user-section-heading">
                <p class="detalis">Reservations</p>
                <h2 class="title">Your Booking</h2>
            </div>

            <div class="bookings-table">
                <?php if ($bookings_query->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <?php if ($user_role === 'admin'): ?><th>User</th><?php endif; ?>
                                <th>Ticket</th>
                                <th>Price</th>
                                <th>Reservation Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = $bookings_query->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $booking['id']; ?></strong></td>
                                    <?php if ($user_role === 'admin'): ?>
                                        <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo htmlspecialchars($booking['ticket_name']); ?></td>
                                    <td><span class="price-badge">&euro;<?php echo number_format($booking['total_price'] ?? $booking['price'], 2); ?></span></td>
                                    <td><span class="date-badge"><i class="fa-solid fa-calendar-day"></i> <?php echo date('d/m/Y H:i', strtotime($booking['booking_date'])); ?></span></td>
                                    <td><span class="status-confirmed"><i class="fa-solid fa-circle-check"></i> Confirmed</span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-bookings">
                        <div class="no-bookings-icon"><i class="fa-regular fa-calendar-xmark"></i></div>
                        <h3>No reservations registered.</h3>
                        <p>Choose a ticket and reserve your next zoo visit.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="booking-cta">
                <a href="tickets.php" class="btn buy-btn">View All Tickets</a>
            </div>
        </section>
    </div>
</main>

<?php

$conn->close();
?>