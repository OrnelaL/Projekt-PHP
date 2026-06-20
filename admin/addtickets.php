<?php
session_start(); 
include "../config/db_conn.php";

$message = "";
$error = "";




// Shtim bilete te re
if (isset($_POST["add_ticket"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $price = floatval($_POST["price"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $icon = $conn->real_escape_string($_POST["icon"]);
    $feature1 = $conn->real_escape_string($_POST["feature1"]);
    $feature2 = $conn->real_escape_string($_POST["feature2"]);
    $feature3 = $conn->real_escape_string($_POST["feature3"]);

    $sql = "INSERT INTO tickets (name, price, description, icon, feature1, feature2, feature3) 
            VALUES ('$name', $price, '$description', '$icon', '$feature1', '$feature2', '$feature3')";

    if ($conn->query($sql)) {
        $message = "New ticket added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Editim bilete
if (isset($_POST["edit_ticket"])) {
    $id = intval($_POST["id"]);
    $name = $conn->real_escape_string($_POST["name"]);
    $price = floatval($_POST["price"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $icon = $conn->real_escape_string($_POST["icon"]);
    $feature1 = $conn->real_escape_string($_POST["feature1"]);
    $feature2 = $conn->real_escape_string($_POST["feature2"]);
    $feature3 = $conn->real_escape_string($_POST["feature3"]);

    $sql = "UPDATE tickets SET 
            name='$name', 
            price=$price, 
            description='$description', 
            icon='$icon',
            feature1='$feature1',
            feature2='$feature2',
            feature3='$feature3'
            WHERE id=$id";

    if ($conn->query($sql)) {
        $message = "Ticket successfully updated!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Fshirje bilete
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $sql = "DELETE FROM tickets WHERE id=$id";

    if ($conn->query($sql)) {
        $message = "Ticket successfully deleted!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Marrim te gjitha biletat
$tickets = $conn->query("SELECT * FROM tickets ORDER BY id DESC");

// Nese kemi nje bilete per editim
$editTicket = null;
if (isset($_GET["edit"])) {
    $id = intval($_GET["edit"]);
    $result = $conn->query("SELECT * FROM tickets WHERE id=$id");
    $editTicket = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="sq">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            color: #667eea;
            font-size: 2em;
        }

        .header a {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .header a:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            animation: slideIn 0.5s;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
        }

        .form-section h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.5em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn {
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .tickets-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .tickets-table h2 {
            background: #667eea;
            color: white;
            padding: 20px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 15px;
            text-align: left;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            color: #555;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .icon-preview {
            font-size: 24px;
        }

        .price-tag {
            font-weight: bold;
            color: #28a745;
            font-size: 18px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            background: #ffc107;
            color: #333;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-edit:hover {
            background: #e0a800;
            transform: scale(1.05);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        .features-list {
            font-size: 12px;
            color: #666;
        }

        .features-list li {
            margin: 3px 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 14px;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Ticket Management</h1>
            <div>
                <a href="dashboard.php">Admin Dahboard</a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Forma per shtim/editim bilete -->
        <div class="form-section">
            <h2><?php echo $editTicket ? 'Edit Ticket' : 'Add New Ticket'; ?></h2>

            <form method="post">
                <?php if ($editTicket): ?>
                    <input type="hidden" name="id" value="<?php echo $editTicket['id']; ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Ticket Name *</label>
                        <input type="text" name="name" required
                            value="<?php echo $editTicket ? htmlspecialchars($editTicket['name']) : ''; ?>"
                            placeholder="Adult Ticket">
                    </div>

                    <div class="form-group">
                        <label>Price (€) *</label>
                        <input type="number" step="0.01" name="price" required
                            value="<?php echo $editTicket ? $editTicket['price'] : ''; ?>"
                            placeholder="10.00">
                    </div>

                    <div class="form-group">
                        <label>Icone *</label>
                        <input type="text" name="icon" required
                            value="<?php echo $editTicket ? htmlspecialchars($editTicket['icon']) : ''; ?>"
                            placeholder="👨">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Description *</label>
                    <textarea name="description" required
                        placeholder="Entry for people over 18 years old"><?php echo $editTicket ? htmlspecialchars($editTicket['description']) : ''; ?></textarea>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Feature 1*</label>
                        <input type="text" name="feature1" required
                            value="<?php echo $editTicket ? htmlspecialchars($editTicket['feature1']) : ''; ?>"
                            placeholder="Full introduction">
                    </div>

                    <div class="form-group">
                        <label>Feature 2 *</label>
                        <input type="text" name="feature2" required
                            value="<?php echo $editTicket ? htmlspecialchars($editTicket['feature2']) : ''; ?>"
                            placeholder="Valid for 1 day">
                    </div>

                    <div class="form-group">
                        <label>Feature 3 *</label>
                        <input type="text" name="feature3" required
                            value="<?php echo $editTicket ? htmlspecialchars($editTicket['feature3']) : ''; ?>"
                            placeholder="Access to all areas">
                    </div>
                </div>

                <div style="margin-top: 25px;">
                    <?php if ($editTicket): ?>
                        <button type="submit" name="edit_ticket" class="btn btn-success">Save Changes</button>
                        <a href="edittickets.php" class="btn btn-cancel">Cancel</a>
                    <?php else: ?>
                        <button type="submit" name="add_ticket" class="btn btn-primary">Add Ticket</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Tabela e biletave -->
        <div class="tickets-table">
            <h2>Ticket List</h2>

            <?php if ($tickets->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Features</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($ticket = $tickets->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $ticket['id']; ?></td>
                                <td class="icon-preview"><?php echo $ticket['icon']; ?></td>
                                <td><strong><?php echo htmlspecialchars($ticket['name']); ?></strong></td>
                                <td class="price-tag">€<?php echo number_format($ticket['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($ticket['description']); ?></td>
                                <td>
                                    <ul class="features-list">
                                        <li>✓ <?php echo htmlspecialchars($ticket['feature1']); ?></li>
                                        <li>✓ <?php echo htmlspecialchars($ticket['feature2']); ?></li>
                                        <li>✓ <?php echo htmlspecialchars($ticket['feature3']); ?></li>
                                    </ul>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="edittickets.php?id=<?php echo $ticket['id']; ?>" class="btn-edit">Edit</a>
                                      <a href="deleteticket.php?id=<?= $ticket['id'] ?>" 
   class="btn-delete"
   onclick="return confirm('Are you sure you want to delete this ticket?')">Delete</a>

                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="padding: 40px; text-align: center; color: #999;">
                    There are no tickets registered. Add your first ticket!
                </p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>

<?php $conn->close(); ?>