<?php
// 1️⃣ Connect to the database
$host = "localhost";
$user = "root";
$pass = "";
$db = "cabin_booking";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2️⃣ Get form data
$cabin_id   = $_POST['cabin_id'];
$start_date = $_POST['checkin'];
$end_date   = $_POST['checkout'];
$reserved_by = $_POST['name'];

// 3️⃣ Optional: check that cabin_id exists
$stmt_check = $conn->prepare("SELECT * FROM cabins WHERE cabin_id = ?");
$stmt_check->bind_param("i", $cabin_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
if ($result->num_rows == 0) {
    die("Error: Selected cabin does not exist.");
}

// ✅ Prevent booking past dates
$today = date('Y-m-d');
if ($start_date < $today || $end_date < $today) {
    die("Error: You cannot book for past dates.");
}

if ($end_date < $start_date) {
    die("Error: Check-out date cannot be before check-in date.");
}

// 3️⃣a Check for overlapping reservations BEFORE inserting
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

// 4️⃣ Insert into reservations
$stmt = $conn->prepare("
    INSERT INTO reservations (cabin_id, start_date, end_date) 
    VALUES (?, ?, ?)
");

$stmt = $conn->prepare("
    INSERT INTO reservations (cabin_id, start_date, end_date, reserved_by)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("isss", $cabin_id, $start_date, $end_date, $reserved_by);

// ✅ Execute the statement and check for success
if ($stmt->execute()) {
    echo "Reservation successful!";
} else {
    echo "Error: " . $stmt->error;
}

// 5️⃣ Close connections
$stmt->close();
$conn->close();