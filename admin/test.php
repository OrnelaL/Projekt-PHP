<?php
include "../includes/header.php";
include "../config/db_conn.php";

// Kontrollo nese admini eshte i loguar (sipas sistemit tend)
// session_start(); if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

// Trajto pergjigjen e adminit
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Messages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { width: 250px; background: #395902; min-height: 100vh; position: fixed; }
        .sidebar h4 { color: #f2a222; padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar a { color: white; display: block; padding: 12px 20px; text-decoration: none; transition: 0.2s; }
        .sidebar a:hover { background: rgba(255,255,255,0.1); }
        .main-content { margin-left: 250px; padding: 30px; }
        .badge-unread { background: #dc3545; }
        .badge-read { background: #6c757d; }
        .badge-replied { background: #395902; }
        .message-card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 15px; 
                        box-shadow: 0 2px 8px rgba(0,0,0,0.07); border-left: 5px solid #dee2e6; transition: 0.2s; }
        .message-card.unread { border-left-color: #dc3545; }
        .message-card.replied { border-left-color: #395902; }
        .message-card.read { border-left-color: #6c757d; }
        .reply-box { display: none; background: #f8f9fa; border-radius: 8px; padding: 15px; margin-top: 15px; }
        .reply-box textarea { width: 100%; border: 1px solid #ddd; border-radius: 6px; padding: 10px; 
                              resize: vertical; min-height: 100px; }
        .btn-reply-toggle { background: #395902; color: white; border: none; padding: 6px 16px; 
                            border-radius: 5px; cursor: pointer; font-size: 14px; }
        .btn-reply-toggle:hover { background: #2d4a01; }
        .btn-send { background: #f2a222; color: white; border: none; padding: 8px 20px; 
                    border-radius: 5px; cursor: pointer; }
        .btn-send:hover { background: #d4891a; }
        .existing-reply { background: #e8f5e9; border-radius: 6px; padding: 12px; margin-top: 10px; 
                          border-left: 4px solid #395902; }
        .stats-card { background: white; border-radius: 10px; padding: 20px; text-align: center; 
                      box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .stats-card .number { font-size: 2rem; font-weight: bold; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4><i class="fa-solid fa-shield-halved me-2"></i>Admin Panel</h4>
    <a href="dashboard.php"><i class="fa-solid fa-envelope me-2"></i>Messages</a>
    <!-- Shto linqe te tjera sipas nevojes -->
</div>

<!-- Main Content -->
<div class="main-content">

    <?php if (isset($_GET["replied"])): ?>
        <div class="alert alert-success" id="repliedMsg">
            <i class="fa-solid fa-check-circle me-2"></i>Reply sent successfully!
        </div>
        <script>
            setTimeout(function() {
                var msg = document.getElementById("repliedMsg");
                if (msg) { msg.style.transition = "opacity 0.8s"; msg.style.opacity = "0"; setTimeout(function(){ msg.remove(); }, 800); }
            }, 3000);
        </script>
    <?php endif; ?>

    <h3 class="mb-4"><i class="fa-solid fa-inbox me-2"></i>Messages</h3>

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
            <p>No messages yet.</p>
        </div>
    <?php else: ?>
        <?php while ($msg = $result->fetch_assoc()): ?>
        <div class="message-card <?= $msg['status'] ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-1">
                        <?= htmlspecialchars($msg['name'] . ' ' . $msg['surname']) ?>
                        <span class="badge badge-<?= $msg['status'] ?> ms-2" 
                              style="font-size:11px; background:<?= $msg['status']=='unread'?'#dc3545':($msg['status']=='replied'?'#395902':'#6c757d') ?>">
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

            <div class="mt-2">
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
                        <label class="mb-1"><strong>Reply to <?= htmlspecialchars($msg['name']) ?>:</strong></label>
                        <textarea name="reply_text" placeholder="Write your reply here..."><?= htmlspecialchars($msg['reply'] ?? '') ?></textarea>
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

<script>
function toggleReply(id) {
    const box = document.getElementById("reply-" + id);
    box.style.display = box.style.display === "block" ? "none" : "block";
}
</script>

</body>
</html>
<?php include "includes/footer.php"; ?>