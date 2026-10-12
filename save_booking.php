<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$dbname = "boyz_codes_db"; // Update with your actual database name if different

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(array("status" => "error", "message" => "Database connection failed"));
    exit();
}

// Get JSON data sent from JavaScript
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->route) && !empty($data->seats) && !empty($data->total_price)) {
    $route = $conn->real_escape_string($data->route);
    $seats = $conn->real_escape_string($data->seats);
    $total_price = intval($data->total_price);

    $sql = "INSERT INTO bookings (route, seats, total_amount) VALUES ('$route', '$seats', $total_price)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("status" => "success", "message" => "Booking saved successfully!"));
    } else {
        echo json_encode(array("status" => "error", "message" => "SQL Error: " . $conn->error));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Incomplete booking data provided."));
}

$conn->close();
?>