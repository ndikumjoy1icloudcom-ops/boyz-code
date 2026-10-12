<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/db.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password)) {
    $email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    $password = $data->password;

    $query = "SELECT id, full_name, email, password_hash, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$email]);

    if($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        if(password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Login successful.",
                "user" => [
                    "id" => $user['id'],
                    "full_name" => $user['full_name'],
                    "email" => $user['email'],
                    "role" => $user['role']
                ]
            ]);
            exit();
        }
    }

    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Please provide email and password."]);
}
?>