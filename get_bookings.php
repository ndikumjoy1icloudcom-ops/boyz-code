<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$username = "root";
$password = "";
$dbname = "boyz_codes_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(array("status" => "error", "message" => "Database connection failed"));
    exit();
}

$sql = "SELECT id, route, seats, total_price, created_at FROM bookings ORDER BY created_at DESC";
$result = $conn->query($sql);

$bookings = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    echo json_encode(array("status" => "success", "data" => $bookings));
} else {
    echo json_encode(array("status" => "success", "data" => array()));
}

$conn->close();
?>