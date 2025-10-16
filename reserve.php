<?php
// Connect to the database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cabin_booking";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$cabin_id   = $_POST['cabin_id'];
$start_date = $_POST['checkin'];
$end_date   = $_POST['checkout'];
$guest_name = $_POST['name'];
$guest_email = $_POST['email'];

// Prevent booking past dates
$today = date('Y-m-d');
if ($start_date < $today || $end_date < $today) {
    die("Error: You cannot book for past dates.");
}
if ($end_date < $start_date) {
    die("Error: Check-out date cannot be before check-in date.");
}

// Optional: check that cabin_id exists
$stmt_check = $conn->prepare("SELECT * FROM cabins WHERE cabin_id = ?");
$stmt_check->bind_param("i", $cabin_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
if ($result->num_rows == 0) {
    die("Error: Selected cabin does not exist.");
}

// Check for overlapping reservations
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
    // Guest exists, get guest_id
    $guest = $result_guest->fetch_assoc();
    $guest_id = $guest['guest_id'];
} else {
    // Insert new guest
    $stmt_insert_guest = $conn->prepare("INSERT INTO guests (name, email) VALUES (?, ?)");
    $stmt_insert_guest->bind_param("ss", $guest_name, $guest_email);
    if ($stmt_insert_guest->execute()) {
        $guest_id = $stmt_insert_guest->insert_id;
    } else {
        die("Error saving guest: " . $stmt_insert_guest->error);
    }
}

// Insert reservation
$stmt_res = $conn->prepare("
    INSERT INTO reservations (cabin_id, guest_id, start_date, end_date, reserved_by)
    VALUES (?, ?, ?, ?, ?)
");
$stmt_res->bind_param("iisss", $cabin_id, $guest_id, $start_date, $end_date, $guest_name);

// Prepare the statement (make sure only one)
$stmt = $conn->prepare("
    INSERT INTO reservations (cabin_id, start_date, end_date, reserved_by)
    VALUES (?, ?, ?, ?)
");

// Check if prepare succeeded
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("isss", $cabin_id, $start_date, $end_date, $reserved_by);

// Execute and show success message with button
if ($stmt->execute()) {
    echo '<div style="padding:1rem; background:#e6f4ea; border:1px solid #a4d5b2; border-radius:8px; text-align:center;">';
    echo '<h2>Reservation successful!</h2>';
    echo '<p>Thank you for booking. Your reservation has been recorded.</p>';
    echo '<button onclick="window.location.href=\'index.php\'" style="padding:0.6rem 1rem; border:none; border-radius:6px; background:#2e5339; color:white; cursor:pointer; font-size:1rem;">Make Another Reservation</button>';
    echo '</div>';
} else {
    echo "Error: " . $stmt->error;
}
// Close statement if it exists
if (isset($stmt) && $stmt) {
    $stmt->close();
}

// Close connection once
if (isset($conn) && $conn) {
    $conn->close();
}
?>
