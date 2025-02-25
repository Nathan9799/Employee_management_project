<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT * FROM employees ORDER BY id DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $employees = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $employees[] = $row;
    }
    
    http_response_code(200);
    echo json_encode($employees);
} catch(PDOException $e) {
    http_response_code(503);
    echo json_encode(["message" => "Unable to retrieve employees"]);
}
?>

