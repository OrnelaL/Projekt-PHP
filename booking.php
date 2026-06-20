<?php
session_start();
require_once 'config/db_conn.php';
include 'includes/header.php';

// Kontrolli per login
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// ID e biletes
if (!isset($_GET['ticket_id'])) {
    header("Location: index.php");
    exit;
}

$ticket_id = intval($_GET['ticket_id']);

// Te dhenat e biletes
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$ticket = $result->fetch_assoc();
$stmt->close();

$message = "";
$error = "";

// Rezervimi
if (isset($_POST['confirm_booking'])) {
    $visit_date = $_POST['visit_date'];
    $num_people = intval($_POST['num_people']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $payment_method = $_POST['payment_method'];

    // Validimi
    if (strtotime($visit_date) < strtotime(date('Y-m-d'))) {
        $error = "The visit date cannot be in the past!";
    } elseif ($num_people < 1 || $num_people > 10) {
        $error = "The number of people must be between 1 and 10!";
    } elseif (!preg_match("/^[0-9+\-\s()]+$/", $phone)) {
        $error = "The phone number is not valid!";
    } else {
        // Llogaritja e çmimit total
        $total_price = $ticket['price'] * $num_people;

        // Ruajtja e rezervimit ne db
        $user = $_SESSION['user'];
        $user_id = $_SESSION['user_id'];
        $ticket_name = $ticket['name'];

        $stmt = $conn->prepare("INSERT INTO bookings (user_id, username, ticket_name, price, visit_date, num_people, phone, payment_method, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdissd", $user_id, $user, $ticket_name, $ticket['price'], $visit_date, $num_people, $phone, $payment_method, $total_price);

        if ($stmt->execute()) {
            $message = "✅ Reservation completed successfully! Ticket number:" . $conn->insert_id;
        } else {
            $error = "Error during booking: " . $conn->error;
        }
    }
}


?>

<div class="booking-container">
    <div class="booking-card">
        <h1 class="title text-center mb-4">
            <i class="fa-solid fa-credit-card" style="color:orange;"></i> Confirm Reservation
        </h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Bileta -->
        <div class="ticket-summary">
            <h2><?php echo htmlspecialchars($ticket['name']); ?></h2>
            <p><?php echo htmlspecialchars($ticket['description']); ?></p>
            <div class="price">€<?php echo number_format($ticket['price'], 2); ?> / person</div>
        </div>

        <!-- Karakteristikat -->
        <div class="features features-list">
            <h3 style="margin-top: 0; color: #0066cc;">Included in this ticket:</h3>
            <ul>
                <li><?php echo htmlspecialchars($ticket['feature1']); ?></li>
                <li><?php echo htmlspecialchars($ticket['feature2']); ?></li>
                <li><?php echo htmlspecialchars($ticket['feature3']); ?></li>
            </ul>
        </div>

        <!-- Forma e rezervimit -->
        <form method="POST" id="bookingForm" class="contact-form">
            <div class="form-group">
                <label>Date of Visit *</label>
                <input type="date"
                    name="visit_date"
                    required
                    min="<?php echo date('Y-m-d'); ?>"
                    value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>

            <div class="form-group">
                <label>Number of Persons *</label>
                <input type="number"
                    name="num_people"
                    id="num_people"
                    required
                    min="1"
                    max="10"
                    value="1"
                    onchange="updatePrice()">
                <small style="color: #666;">Maximum 10 people per reservation</small>
            </div>

            <div class="form-group">
                <label>Phone Number *</label>
                <input type="tel"
                    name="phone"
                    required
                    placeholder="+355 XX XXX XXXX"
                    pattern="[0-9+\-\s()]+">
            </div>

            <div class="form-group">
                <label>Payment Method*</label>
                <select name="payment_method" required>
                    <option value="">Select payment methods</option>
                    <option value="cash">Cash (incoming)</option>
                    <option value="card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>

            <!-- Llogaritja e çmimit -->
            <div class="price-calculation">
                <h3 style="margin-top: 0;">Payment Summary:</h3>
                <div class="price-row">
                    <span>Price per person:</span>
                    <span><strong>€<?php echo number_format($ticket['price'], 2); ?></strong></span>
                </div>
                <div class="price-row">
                    <span>Number of people:</span>
                    <span id="people_display"><strong>1</strong></span>
                </div>
                <div class="price-row price-total">
                    <span>TOTAL:</span>
                    <span id="total_price">€<?php echo number_format($ticket['price'], 2); ?></span>
                </div>
            </div>

            <div class="form-group pb-2">
                <label style="display: flex; align-items:flex-end;">
                    <input type="checkbox" required style="width:10%; text-align:start;">
                    <p class="mb-0">Accept <a href="#" style="color: #667eea;">terms and conditions</a></p>
                </label>
            </div>

            <button type="submit" name="confirm_booking" class="btn-confirm">
                <i class="fa-solid fa-check-to-slot"></i> Confirm Reservation
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #666;">
            <a href="tickets.php" style="color: #667eea;"><i class="fa-solid fa-arrow-left"></i> Back to tickets</a>
        </p>
    </div>
</div>

<script>
    function updatePrice() {
        const numPeople = document.getElementById('num_people').value;
        const pricePerPerson = <?php echo $ticket['price']; ?>;
        const total = numPeople * pricePerPerson;

        document.getElementById('people_display').innerHTML = '<strong>' + numPeople + '</strong>';
        document.getElementById('total_price').textContent = '€' + total.toFixed(2);
    }
</script>

<?php
// include 'includes/footer.php';
$conn->close();
?>