<?php
// reserve.php
// Basic server-side reservation handling, price calculation and email sending.
// IMPORTANT: adjust DB credentials if needed.

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "12345678Riina";
$dbName = "cabin_booking";

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}

// helper: sanitize POST
function post($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

$cabin = post('cabin');            // helmi1 | helmi2 | helmi3
$checkin = post('checkin');        // YYYY-MM-DD
$checkout = post('checkout');      // YYYY-MM-DD
$email = post('email');
$people = intval(post('people') ?: 1);
$cleaning = isset($_POST['cleaning']) ? 1 : 0;
$linen = isset($_POST['linen']) ? 1 : 0;

// validate basic fields
$allowed = ['helmi1','helmi2','helmi3'];
if (!in_array($cabin, $allowed)) {
    die("Invalid cabin selected.");
}
if (!$checkin || !$checkout) {
    die("Please provide check-in and check-out dates.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

// compute nights
$in = new DateTime($checkin);
$out = new DateTime($checkout);
$interval = $out->diff($in);
$nights = (int)$interval->format('%a');

if ($nights < 2) {
    die("Minimum stay is 2 nights.");
}

// pricing tiers - same logic as client
$pricing = [
    'helmi1' => ['2' => 75, '3-5' => 70, '6+' => 50, 'cleaning' => 60, 'linen' => 15],
    'helmi2' => ['2' => 120, '3-5' => 110, '6+' => 70, 'cleaning' => 75, 'linen' => 20],
    'helmi3' => ['2' => 110, '3-5' => 100, '6+' => 60, 'cleaning' => 85, 'linen' => 20],
];

$tiers = $pricing[$cabin];
if ($nights >= 6) {
    $rate = $tiers['6+'];
} elseif ($nights >= 3 && $nights <= 5) {
    $rate = $tiers['3-5'];
} else {
    $rate = $tiers['2'];
}

$total = $rate * $nights;
if ($cleaning) $total += $tiers['cleaning'];
if ($linen) $total += ($tiers['linen'] * max(1, $people));

// generate reservation code (random 6 chars)
$reservation_code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
$created_at = date('Y-m-d H:i:s');
$payment_status = 'pending';

// insert into DB
$stmt = $mysqli->prepare("INSERT INTO reservations (cabin, checkin, checkout, people, cleaning, linen, total_price, email, reservation_code, payment_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param("sssiiisssss", $cabin, $checkin, $checkout, $people, $cleaning, $linen, $total, $email, $reservation_code, $payment_status, $created_at);
$exec = $stmt->execute();
if (!$exec) {
    die("Database insert failed: " . $stmt->error);
}
$resId = $stmt->insert_id;
$stmt->close();

// Send email with payment instructions
// NOTE: configure From header for your domain / server
$subject = "Reservation received — payment instructions (Reservation #$resId)";

$bankAccount = "FI00 1234 5600 0000 00 (Your company account)";
$companyName = "Siiranmäki Cabins";
$paymentDueDate = (new DateTime($checkin))->modify('-7 days')->format('Y-m-d');

$message = "Hello,\n\n"
  . "Thank you for your reservation at {$companyName}.\n\n"
  . "Reservation details:\n"
  . "Reservation ID: {$resId}\n"
  . "Cabin: {$cabin}\n"
  . "Check-in: {$checkin}\n"
  . "Check-out: {$checkout}\n"
  . "Nights: {$nights}\n"
  . "Cleaning fee added: " . ($cleaning ? 'Yes' : 'No') . "\n"
  . "Linen fee added: " . ($linen ? 'Yes' : 'No') . " (people: {$people})\n\n"
  . "Total amount: {$total} EUR\n\n"
  . "Payment instructions:\n"
  . "- Please pay the reservation fee to our account at least one week (7 days) before the booking start date.\n"
  . "- Payment due date: {$paymentDueDate}\n"
  . "- Bank account: {$bankAccount}\n"
  . "- Use reservation ID {$resId} as payment reference.\n\n"
  . "Important: Once we have received and confirmed the payment in our account, we will send you the code for the cabin's key box by email.\n\n"
  . "If you have any questions, reply to this email.\n\n"
  . "Best regards,\n"
  . "{$companyName}\n";

$headers = "From: reservations@example.com\r\nReply-To: reservations@example.com\r\n";
$mailSuccess = mail($email, $subject, $message, $headers);

// Provide a simple HTML response back to the user
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>Reservation received</title></head>
<body>
  <h1>Reservation received</h1>
  <p>Thank you — your reservation has been recorded (ID: <strong><?php echo htmlspecialchars($resId); ?></strong>).</p>
  <p>A confirmation email with payment instructions was sent to <strong><?php echo htmlspecialchars($email); ?></strong>.</p>
  <p><strong>Total to pay:</strong> <?php echo htmlspecialchars($total); ?> €</p>
  <p>Payment must arrive at our account by <strong><?php echo htmlspecialchars($paymentDueDate); ?></strong>. Once payment is received, we will send the key-box code to your email.</p>

  <?php if (!$mailSuccess): ?>
    <p><em>Note: we were unable to send email from this server (mail() returned false). If you don't receive an email, contact us and provide your reservation ID.</em></p>
  <?php endif; ?>

  <p><a href="index.php">Back to site</a></p>
</body>
</html>
<?php
$mysqli->close();
