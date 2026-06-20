<?php
include "../config/db_conn.php";
include "../includes/header.php";

session_start();


$message = "";
$error = "";

// Marrim ID-ne e biletes nga URL
if (!isset($_GET["id"])) {
    header("Location: ");
    exit;
}

$ticketId = intval($_GET["id"]);

// Marrim te dhenat e biletes
$result = $conn->query("SELECT * FROM tickets WHERE id=$ticketId");
if ($result->num_rows == 0) {
    header("Location: dashboard.php");
    exit;
}
$ticket = $result->fetch_assoc();

// Perditesimi i biletes
if (isset($_POST["update_ticket"])) {
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
            WHERE id=$ticketId";
    
    if ($conn->query($sql)) {
        $message = "Ticket successfully updated!";
        
        $result = $conn->query("SELECT * FROM tickets WHERE id=$ticketId");
        $ticket = $result->fetch_assoc();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tickets - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>      
       body {
           
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
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
        
        .back-btn {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .back-btn:hover {
            background: #5a6268;
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
        
        .ticket-preview {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            border: 2px solid #dee2e6;
        }
        
        .ticket-preview .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }
        
        .ticket-preview h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .ticket-preview .price {
            font-size: 36px;
            color: #28a745;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .ticket-preview .description {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        
        .ticket-preview .features {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: left;
            display: inline-block;
        }
        
        .ticket-preview .features ul {
            list-style: none;
            padding: 0;
        }
        
        .ticket-preview .features li {
            padding: 8px 0;
            color: #555;
        }
        
        .ticket-preview .features li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-right: 8px;
        }
        
        .form-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
        }
        
        .form-section h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.5em;
            text-align: center;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            font-size: 14px;
        }
        
        .form-group input,
        .form-group textarea {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-group small {
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .icon-input {
            font-size: 24px;
            text-align: center;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            flex: 1;
            padding: 16px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-update {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        
        .info-box p {
            margin: 0;
            color: #1976D2;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 1.5em;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Edit Tickets</h1>
        <a href="dashboard.php" class="back-btn">Admin Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Preview i biletes -->
    <div class="ticket-preview">
        <div class="icon"><?php echo $ticket['icon']; ?></div>
        <h2><?php echo htmlspecialchars($ticket['name']); ?></h2>
        <p class="description"><?php echo htmlspecialchars($ticket['description']); ?></p>
        <div class="price">€<?php echo number_format($ticket['price'], 2); ?></div>
        <div class="features">
            <ul>
                <li><?php echo htmlspecialchars($ticket['feature1']); ?></li>
                <li><?php echo htmlspecialchars($ticket['feature2']); ?></li>
                <li><?php echo htmlspecialchars($ticket['feature3']); ?></li>
            </ul>
        </div>
    </div>

    <!-- Forma per editim -->
    <div class="form-section">
        <h2>Edit Information</h2>
        
        <div class="info-box">
            <p>All fields marked with an (*) are required. Changes will be reflected directly on the main page.</p>
        </div>

        <form method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label>Ticket Name *</label>
                    <input type="text" 
                           name="name" 
                           required 
                           value="<?php echo htmlspecialchars($ticket['name']); ?>"
                           placeholder="p.sh: Bilete Adult">
                    <small>The name that will be displayed to customers</small>
                </div>
                
                <div class="form-group">
                    <label>Price (€) *</label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           name="price" 
                           required 
                           value="<?php echo $ticket['price']; ?>"
                           placeholder="p.sh: 10.00">
                    <small>Price</small>
                </div>
                
                <div class="form-group">
                    <label>Icon *</label>
                    <input type="text" 
                           name="icon" 
                           required 
                           class="icon-input"
                           value="<?php echo htmlspecialchars($ticket['icon']); ?>"
                           placeholder="👨"
                           maxlength="10">
                    <small>Add an emoji to represent the ticket</small>
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Description *</label>
                <textarea name="description" 
                          required 
                          placeholder=" Entry for people over 18 years old"><?php echo htmlspecialchars($ticket['description']); ?></textarea>
                <small>Short description of the ticket</small>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Characteristics 1 *</label>
                    <input type="text" 
                           name="feature1" 
                           required 
                           value="<?php echo htmlspecialchars($ticket['feature1']); ?>"
                           placeholder="Full introduction">
                </div>
                
                <div class="form-group">
                    <label>Characteristics 2 *</label>
                    <input type="text" 
                           name="feature2" 
                           required 
                           value="<?php echo htmlspecialchars($ticket['feature2']); ?>"
                           placeholder="Valid for 1 day">
                </div>
                
                <div class="form-group">
                    <label>Characteristics 3 *</label>
                    <input type="text" 
                           name="feature3" 
                           required 
                           value="<?php echo htmlspecialchars($ticket['feature3']); ?>"
                           placeholder="Access to all areas">
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" name="update_ticket" class="btn btn-update">
                  Save Changes
                </button>
                <a href="" class="btn btn-cancel">
                  DISCARD
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Live preview kur ndryshohen fushat
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const priceInput = document.querySelector('input[name="price"]');
    const iconInput = document.querySelector('input[name="icon"]');
    const descInput = document.querySelector('textarea[name="description"]');
    const feature1 = document.querySelector('input[name="feature1"]');
    const feature2 = document.querySelector('input[name="feature2"]');
    const feature3 = document.querySelector('input[name="feature3"]');
    
    const previewName = document.querySelector('.ticket-preview h2');
    const previewPrice = document.querySelector('.ticket-preview .price');
    const previewIcon = document.querySelector('.ticket-preview .icon');
    const previewDesc = document.querySelector('.ticket-preview .description');
    const previewFeatures = document.querySelectorAll('.ticket-preview .features li');
    
    nameInput.addEventListener('input', () => previewName.textContent = nameInput.value || 'Emri i Biletes');
    priceInput.addEventListener('input', () => previewPrice.textContent = '€' + (parseFloat(priceInput.value) || 0).toFixed(2));
    iconInput.addEventListener('input', () => previewIcon.textContent = iconInput.value || '🎫');
    descInput.addEventListener('input', () => previewDesc.textContent = descInput.value || 'Pershkrimi i biletes');
    feature1.addEventListener('input', () => previewFeatures[0].innerHTML = '✓ ' + (feature1.value || 'Karakteristika 1'));
    feature2.addEventListener('input', () => previewFeatures[1].innerHTML = '✓ ' + (feature2.value || 'Karakteristika 2'));
    feature3.addEventListener('input', () => previewFeatures[2].innerHTML = '✓ ' + (feature3.value || 'Karakteristika 3'));
});
</script>

</body>
</html>

<?php $conn->close(); ?>