<?php
session_start();
require_once 'config/db_conn.php';
include 'includes/header.php';
include 'includes/navbar.php';

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Ruaj te dhenat ne session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect sipas rolit
                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                    header("Location: user.php");
                }
                exit;
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "User not found!";
        }
        $stmt->close();
    }
}
?>

<div class="login-page">
    <div class="container login-now">
        <h2 class="title text-center">Login</h2>

        <?php if ($error): ?>
            <p style="color:red; text-align:center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" class="contact-form">
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit" name="login" class="btn buy-btn w-100">Login</button>
        </form>

        <p class="pt-3">Don't have an account? <a href="register.php" class="read fw-bold">Register HERE</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>