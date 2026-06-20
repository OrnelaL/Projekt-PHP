<?php
session_start();
include "../config/db_conn.php";
include "../includes/header.php";

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Check if user is admin
$user_role = $_SESSION['role'] ?? '';
if ($user_role !== "admin") {
    header("Location: ../index.php");
    exit;
}

// Get statistics from database
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$total_animals = $conn->query("SELECT COUNT(*) as count FROM kafshet")->fetch_assoc()['count'];
$total_blogs = $conn->query("SELECT COUNT(*) as count FROM blog")->fetch_assoc()['count'];

// Rezervimet e sotme
$today = date('Y-m-d');
$today_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE DATE(booking_date) = '$today'")->fetch_assoc()['count'];

// Rezervimet e muajit
$current_month = date('Y-m');
$month_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE DATE_FORMAT(booking_date, '%Y-%m') = '$current_month'")->fetch_assoc()['count'];

// Rezervimet sipas tipit te biletes
$tickets_by_type = $conn->query("
    SELECT ticket_name, COUNT(*) as count 
    FROM bookings 
    GROUP BY ticket_name 
    ORDER BY count DESC
");

// Te ardhurat e sotme (opsionale)
$today_revenue = $conn->query("SELECT SUM(price) as total FROM bookings WHERE DATE(booking_date) = '$today'")->fetch_assoc()['total'] ?? 0;

// Te ardhurat e muajit (opsionale)
$month_revenue = $conn->query("SELECT SUM(price) as total FROM bookings WHERE DATE_FORMAT(booking_date, '%Y-%m') = '$current_month'")->fetch_assoc()['total'] ?? 0;


// Mesazhet 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reply_id"])) {
    $id    = intval($_POST["reply_id"]);
    $reply = $conn->real_escape_string($_POST["reply_text"]);
    $to_email = $conn->real_escape_string($_POST["to_email"]);
    $to_name  = $conn->real_escape_string($_POST["to_name"]);

    // Ruaj pergjigjen ne databaze
    $sql = "UPDATE messages SET reply = '$reply', status = 'replied' WHERE id = $id";
    $conn->query($sql);

    // Dergo email tek perdoruesi
    $subject  = "Reply to your message - YourSite";
    $headers  = "From: admin@yoursite.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body = "
        <h3>Hello $to_name,</h3>
        <p>Thank you for contacting us. Here is our reply:</p>
        <blockquote style='border-left:4px solid #395902; padding-left:12px; color:#333;'>
            $reply
        </blockquote>
        <br>
        <p>Best regards,<br>Admin Team</p>
    ";
    mail($to_email, $subject, $body, $headers);

    header("Location: dashboard.php?replied=1");
    exit();
}

// Sheno mesazhin si te lexuar kur hapet
if (isset($_GET["read_id"])) {
    $read_id = intval($_GET["read_id"]);
    $conn->query("UPDATE messages SET status = 'read' WHERE id = $read_id AND status = 'unread'");
}

// Merr te gjitha mesazhet
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");

$user_name = $_SESSION['user'];
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
            <a href="../logout.php" class=" fw-bold logout-btn">Logout</a>
        </div>
    </div>
</div>

<section class="dashboard-part">
    <div class="container">
        <div class="row">

            <div class="col-lg-4">
                <!-- Management Sections -->
                <div class=" row management my-4">
                    <!-- Blog Management -->
                    <div class="col-lg-12 management-part">
                        <h2>Blog Management</h2>
                        <div class="button-group">
                            <a href="addblog.php" class="btn btn-add">Add Blog</a>
                            <a href="bloglist.php" class="btn btn-edit">Edit Blog</a>
                            <a href="bloglist.php" class="btn btn-delete">Delete Blog</a>
                        </div>
                    </div>

                    <!-- Animal Management -->
                    <div class="col-lg-12 management-part">
                        <h2>Animal Management</h2>
                        <div class="button-group">
                            <a href="addanimal.php" class="btn btn-add">Add Animal</a>
                            <a href="animallist.php" class="btn btn-edit">Edit Animal</a>
                            <a href="animallist.php" class="btn btn-delete">Delete Animal</a>
                        </div>
                    </div>

                    <!-- Ticket Management -->
                    <div class="col-lg-12 management-part">
                        <h2>Ticket Management</h2>
                        <div class="button-group">
                            <a href="addtickets.php" class="btn btn-add">Edit Tickets</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <!-- Statistics -->
                <div class="statisticss mt-5">
                    <div class="stat-box">
                        <div class="stat-label">Total Bookings</div>
                        <div class="stat-value"><?php echo $total_bookings; ?></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Today's Bookings</div>
                        <div class="stat-value"><?php echo $today_bookings; ?></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">This Month</div>
                        <div class="stat-value"><?php echo $month_bookings; ?></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Animals in Zoo</div>
                        <div class="stat-value"><?php echo $total_animals; ?></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Events Organized</div>
                        <div class="stat-value"><?php echo $total_blogs; ?></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Today's Revenue</div>
                        <div class="stat-value">€<?php echo number_format($today_revenue, 2); ?></div>
                    </div>
                </div>

                <!-- Rezervimet sipas tipit te biletes -->
                <div class="card  booking-card" style="margin-bottom: 1.5rem;">
                    <h2>Bookings by Ticket Type</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #ecf0f1;">
                                <th class="" style="padding: 0.75rem; text-align: left; color:black">Ticket Type</th>
                                <th style="padding: 0.75rem; text-align: right; color:black">Total Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $tickets_by_type->fetch_assoc()): ?>
                                <tr style="border-bottom: 1px solid #ecf0f1;">
                                    <td style="padding: 0.75rem; color:black"><?php echo htmlspecialchars($row['ticket_name']); ?></td>
                                    <td style="padding: 0.75rem; text-align: right; font-weight: 600; color: #27ae60;">
                                        <?php echo $row['count']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12">
                <!-- Mesazhet  -->
                <div class="main-content">

                    <?php if (isset($_GET["replied"])): ?>
                        <div class="alert alert-success" id="repliedMsg">
                            <i class="fa-solid fa-check-circle me-2"></i>Reply sent successfully!
                        </div>
                        <script>
                            setTimeout(function() {
                                var msg = document.getElementById("repliedMsg");
                                if (msg) {
                                    msg.style.transition = "opacity 0.8s";
                                    msg.style.opacity = "0";
                                    setTimeout(function() {
                                        msg.remove();
                                    }, 800);
                                }
                            }, 3000);
                        </script>
                    <?php endif; ?>

                    <h3 class="mb-4 text-white">Messages</h3>

                    <!-- Statistikat -->
                    <?php
                    $total    = $conn->query("SELECT COUNT(*) as c FROM messages")->fetch_assoc()["c"];
                    $unread   = $conn->query("SELECT COUNT(*) as c FROM messages WHERE status='unread'")->fetch_assoc()["c"];
                    $replied  = $conn->query("SELECT COUNT(*) as c FROM messages WHERE status='replied'")->fetch_assoc()["c"];
                    ?>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="number text-dark"><?= $total ?></div>
                                <div class="text-muted">Total Messages</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="number text-danger"><?= $unread ?></div>
                                <div class="text-muted">Unread</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="number" style="color:#395902"><?= $replied ?></div>
                                <div class="text-muted">Replied</div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista e Mesazheve -->
                    <?php if ($result->num_rows === 0): ?>
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-inbox fa-3x mb-3"></i>
                            <p >No messages yet.</p>
                        </div>
                    <?php else: ?>
                        <?php while ($msg = $result->fetch_assoc()): ?>
                            <div class="message-card <?= $msg['status'] ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">
                                            <?= htmlspecialchars($msg['name'] . ' ' . $msg['surname']) ?>
                                            <span class="badge badge-<?= $msg['status'] ?> ms-2"
                                                style="font-size:11px; background:<?= $msg['status'] == 'unread' ? '#dc3545' : ($msg['status'] == 'replied' ? '#395902' : '#6c757d') ?>">
                                                <?= strtoupper($msg['status']) ?>
                                            </span>
                                        </h5>
                                        <small class="text-muted">
                                            <i class="fa-solid fa-envelope me-1"></i><?= htmlspecialchars($msg['email']) ?>
                                            &nbsp;|&nbsp;
                                            <i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($msg['phone']) ?>
                                            &nbsp;|&nbsp;
                                            <i class="fa-solid fa-clock me-1"></i><?= date('d M Y, H:i', strtotime($msg['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>

                                <div class="mt-2 text-dark">
                                    <strong>Subject:</strong> <?= htmlspecialchars($msg['subject']) ?>
                                </div>
                                <div class="mt-1">
                                    <strong>Message:</strong> <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                </div>

                                <!-- Nese ka pergjigje te meparshme -->
                                <?php if (!empty($msg['reply'])): ?>
                                    <div class="existing-reply mt-2">
                                        <strong><i class="fa-solid fa-reply me-1"></i>Your reply:</strong><br>
                                        <?= nl2br(htmlspecialchars($msg['reply'])) ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Butoni dhe forma e pergjigjes -->
                                <div class="mt-3">
                                    <button class="btn-reply-toggle" onclick="toggleReply(<?= $msg['id'] ?>)">
                                        <i class="fa-solid fa-reply me-1"></i>
                                        <?= !empty($msg['reply']) ? 'Edit Reply' : 'Reply' ?>
                                    </button>

                                    <div class="reply-box" id="reply-<?= $msg['id'] ?>">
                                        <form method="POST">
                                            <input type="hidden" name="reply_id" value="<?= $msg['id'] ?>">
                                            <input type="hidden" name="to_email" value="<?= htmlspecialchars($msg['email']) ?>">
                                            <input type="hidden" name="to_name" value="<?= htmlspecialchars($msg['name']) ?>">
                                            <label class="mb-1 text-dark"><strong>Reply to <?= htmlspecialchars($msg['name']) ?>:</strong></label>
                                            <textarea name="reply_text"style="border-color:black; color:black" placeholder="Write your reply here..."><?= htmlspecialchars($msg['reply'] ?? '') ?></textarea>
                                            <div class="mt-2">
                                                <button type="submit" class="btn-send">
                                                    <i class="fa-solid fa-paper-plane me-1"></i>Send Reply
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary ms-2"
                                                    onclick="toggleReply(<?= $msg['id'] ?>)">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function toggleReply(id) {
        const box = document.getElementById("reply-" + id);
        box.style.display = box.style.display === "block" ? "none" : "block";
    }
</script>

<?php
$conn->close();
?>