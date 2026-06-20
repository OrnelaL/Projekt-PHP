<?php
session_start();
require_once 'config/db_conn.php';
include 'includes/header.php';
include 'includes/navbar.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Name validation
    if (!preg_match("/^[a-zA-ZeeçÇ\s]+$/", $name)) {
        $error = "Name should contain just letters.";
    }

    elseif (strlen($name) < 3) {
        $error = "Name should have ate least 3 characters.";
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email is not valid!";
    }

    elseif (!preg_match(
        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
        $password
    )) {
        $error = "Password must have at least 8 characters, 1 uppercase letter, 1 lowercase letter, 1 number and 1 symbol!";
    }

    // Password = Confirm Password
    elseif ($password !== $password_confirm) {
        $error = "Password and confirmed password do not match!";
    } else {
        // Kontroll email ekzistues
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "This email is already used!";
        } else {

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')"
            );
            $stmt->bind_param("sss", $name, $email, $password_hashed);

            if ($stmt->execute()) {
                $success = "Registration was successful! You can log in.";
            } else {
                $error = "Error during registration!";
            }
        }
        $stmt->close();
    }
}
?>

<div class="login-page">
    <div class="container login-now">

        <h2 class="title text-center">Register</h2>

        <?php if ($error): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color:green;"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" class="contact-form">
            <input type="text" name="name" placeholder="Name" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <input type="password" name="password_confirm" placeholder="Confirmed Password" required><br><br>
            <button type="submit" name="register" class="btn buy-btn w-100">Signup</button>
        </form>

        <p class="pt-3">Login <a href="login.php" class="read fw-bold">HERE</a></p>
    </div>
</div>
<?php
include 'includes/footer.php';
?>