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

<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1>👋 Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>📧 <?php echo htmlspecialchars($user_email); ?></p>
        <span class="user-badge">
            <?php echo $user_role === 'admin' ? 'Admin' : '👤 User'; ?>
        </span>
    </div>

    <div class="row g-3">
        <div class=" col-lg-4 ">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-number"><?php echo $total_bookings; ?></div>
                <div class="stat-label">Booking</div>
            </div>
        </div>
        <div class="col-lg-4 ">
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
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2 class="section-title">🚀 Quick Actions</h2>
        <div class="action-buttons">
            <a href="index.php" class="action-btn">🏠 Home</a>
            <a href="tickets.php" class="action-btn">🎟️ Tickets</a>
            <?php if ($user_role === 'admin'): ?>
                <a href="admin/dashboard.php" class="action-btn admin">🛠️ Admin Panel</a>
            <?php endif; ?>
            <a href="logout.php" class="action-btn" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">🚪 Logout</a>
        </div>
    </div>

    <!-- Rezervimet -->
    <h2 class="section-title">Your Booking</h2>
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
                            <td><span class="price-badge">€<?php echo number_format($booking['price'], 2); ?></span></td>
                            <td><span class="date-badge">📅 <?php echo date('d/m/Y H:i', strtotime($booking['booking_date'])); ?></span></td>
                            <td><span style="color: #28a745; font-weight: 600;">✓ Confirmed</span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-bookings">
                <div class="no-bookings-icon">📭</div>
                <h3>No reservations registered.</h3>
            </div>
        <?php endif; ?>
    </div>



    <div style="text-align: center; margin: 40px 0;">
        <a href="tickets.php" class="btn buy-btn" style="font-size: 1.1em; padding: 15px 40px;"> View All Tickets</a>
    </div>
</div>
</div>
<?php

$conn->close();
?>