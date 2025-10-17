<?php
// reserve.php - adapted for existing schema

// DB connection
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "12345678Riina";
$dbName = "cabin_booking";

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}

// Helper: sanitize POST
function post($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

// Form inputs
$cabin_id = intval(post('cabin_id'));     // numeric ID from cabins table
$checkin  = post('checkin');              // YYYY-MM-DD
$checkout = post('checkout');             // YYYY-MM-DD
$guest_name = post('name');               // full name
$email      = post('email');

// Validate basic fields
if (!$cabin_id || !$checkin || !$checkout || !$guest_name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Missing or invalid fields.");
}

// Compute nights
$in = new DateTime($checkin);
$out = new DateTime($checkout);
$interval = $out->diff($in);
$nights = (int)$interval->format('%a');
if ($nights < 1) die("Check-out must be after check-in.");

// 1️⃣ Ensure guest exists or create new guest
$stmt = $mysqli->prepare("SELECT guest_id FROM guests WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($guest_id);
$stmt->fetch();
$stmt->close();

if (!$guest_id) {
    // Insert new guest
    $stmt = $mysqli->prepare("INSERT INTO guests (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $guest_name, $email);
    $stmt->execute();
    $guest_id = $stmt->insert_id;
    $stmt->close();
}

// 2️⃣ Insert reservation
$created_at = date('Y-m-d H:i:s');
$stmt = $mysqli->prepare("INSERT INTO reservations (cabin_id, guest_id, start_date, end_date, reserved_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
$reserved_by = $guest_name;  // optional
$updated_at = $created_at;
$stmt->bind_param("iisssss", $cabin_id, $guest_id, $checkin, $checkout, $reserved_by, $created_at, $updated_at);
if (!$stmt->execute()) {
    die("Insert failed: " . $stmt->error);
}
$resId = $stmt->insert_id;
$stmt->close();

// 3️⃣ Send email (optional)
$subject = "Reservation received (ID #$resId)";
$message = "Hello $guest_name,\n\nYour reservation (ID: $resId) for cabin #$cabin_id from $checkin to $checkout has been recorded.\n\nThank you!";
$headers = "From: reservations@example.com\r\n";
$mailSuccess = mail($email, $subject, $message, $headers);

// 4️⃣ HTML response
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>Reservation Received</title></head>
<body>
<h1>Reservation Received</h1>
<p>Thank you, <?php echo htmlspecialchars($guest_name); ?>. Your reservation ID is <strong><?php echo htmlspecialchars($resId); ?></strong>.</p>
<p>Confirmation email sent to: <strong><?php echo htmlspecialchars($email); ?></strong></p>
<?php if (!$mailSuccess): ?>
    <p><em>Warning: Email could not be sent. Contact us with your reservation ID.</em></p>
<?php endif; ?>
<p><a href="index.php">Back to site</a></p>
</body>
</html>
<?php
$mysqli->close();
?>
