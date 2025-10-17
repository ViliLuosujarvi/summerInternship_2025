<?php
// Connect to database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cabin_booking";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data safely
$cabin_id   = isset($_POST['cabin_id']) ? intval($_POST['cabin_id']) : 0;
$guest_name = isset($_POST['name']) ? trim($_POST['name']) : '';
$guest_email= isset($_POST['email']) ? trim($_POST['email']) : '';
$people     = isset($_POST['people']) ? intval($_POST['people']) : 1;
$cleaning   = isset($_POST['cleaning']) ? 1 : 0;
$linen      = isset($_POST['linen']) ? 1 : 0;
$start_date = isset($_POST['checkin']) ? $_POST['checkin'] : '';
$end_date   = isset($_POST['checkout']) ? $_POST['checkout'] : '';

// Validate required fields
if (!$cabin_id || !$guest_name || !$guest_email || !$start_date || !$end_date) {
    die("Error: All fields are required.");
}

// Validate dates
$today = date('Y-m-d');
if ($start_date < $today || $end_date < $today) {
    die("Error: You cannot book for past dates.");
}
if ($end_date < $start_date) {
    die("Error: Check-out date cannot be before check-in date.");
}

// Check cabin exists
$stmt_check = $conn->prepare("SELECT * FROM cabins WHERE cabin_id = ?");
$stmt_check->bind_param("i", $cabin_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
if ($result->num_rows == 0) {
    die("Error: Selected cabin does not exist.");
}

// Check overlapping reservations
$stmt_overlap = $conn->prepare("
    SELECT * FROM reservations 
    WHERE cabin_id = ? 
      AND start_date < ? 
      AND end_date > ?
");
$stmt_overlap->bind_param("iss", $cabin_id, $end_date, $start_date);
$stmt_overlap->execute();
$result_overlap = $stmt_overlap->get_result();
if ($result_overlap->num_rows > 0) {
    die("Error: Cabin already booked for these dates.");
}

// Insert or get guest
$stmt_guest = $conn->prepare("SELECT guest_id FROM guests WHERE email = ?");
$stmt_guest->bind_param("s", $guest_email);
$stmt_guest->execute();
$result_guest = $stmt_guest->get_result();

if ($result_guest->num_rows > 0) {
    $guest = $result_guest->fetch_assoc();
    $guest_id = $guest['guest_id'];
} else {
    $stmt_insert_guest = $conn->prepare("INSERT INTO guests (name, email) VALUES (?, ?)");
    $stmt_insert_guest->bind_param("ss", $guest_name, $guest_email);
    if ($stmt_insert_guest->execute()) {
        $guest_id = $stmt_insert_guest->insert_id;
    } else {
        die("Error saving guest: " . $stmt_insert_guest->error);
    }
    if (isset($stmt_insert_guest)) $stmt_insert_guest->close();
}

// Calculate total price
$per_night = 100; // default base price; adjust per cabin logic if needed
$nights = (strtotime($end_date) - strtotime($start_date)) / (60*60*24);
$total_price = $per_night * $nights;
if ($cleaning) $total_price += 100;
if ($linen) $total_price += 25 * $people;

// Insert reservation
$stmt_res = $conn->prepare("
    INSERT INTO reservations (cabin_id, guest_id, start_date, end_date, reserved_by, people, cleaning, linen, total_price)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt_res->bind_param("iisssiiid", $cabin_id, $guest_id, $start_date, $end_date, $guest_name, $people, $cleaning, $linen, $total_price);

if ($stmt_res->execute()) {
    echo '<div style="padding:1rem; background:#e6f4ea; border:1px solid #a4d5b2; border-radius:8px; text-align:center;">';
    echo '<h2>Reservation successful!</h2>';
    echo '<p>Thank you, ' . htmlspecialchars($guest_name) . '. Your reservation has been recorded.</p>';
    echo '<p>Total Price: €' . number_format($total_price,2) . '</p>';
    echo '<button onclick="window.location.href=\'index.php\'" style="padding:0.6rem 1rem; border:none; border-radius:6px; background:#2e5339; color:white; cursor:pointer; font-size:1rem;">Make Another Reservation</button>';
    echo '</div>';
} else {
    echo "Error: " . $stmt_res->error;
}

// Close statements and connection
if (isset($stmt_check)) $stmt_check->close();
if (isset($stmt_overlap)) $stmt_overlap->close();
if (isset($stmt_guest)) $stmt_guest->close();
if (isset($stmt_res)) $stmt_res->close();
if (isset($conn)) $conn->close();
?>
