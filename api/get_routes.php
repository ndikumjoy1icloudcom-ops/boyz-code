<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Include database connection
require_once __DIR__ . '/../config/db.php';

try {
    $departure = isset($_GET['departure']) ? trim($_GET['departure']) : '';
    $arrival = isset($_GET['arrival']) ? trim($_GET['arrival']) : '';

    $query = "SELECT id, departure_city, arrival_city, price_fcfa, departure_time, total_seats FROM routes WHERE 1=1";
    $params = [];

    if (!empty($departure)) {
        $query .= " AND departure_city = ?";
        $params[] = $departure;
    }

    if (!empty($arrival)) {
        $query .= " AND arrival_city = ?";
        $params[] = $arrival;
    }

    $query .= " ORDER BY departure_time ASC";

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $routes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "count" => count($routes),
        "data" => $routes
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch routes: " . $e->getMessage()
    ]);
}
?>