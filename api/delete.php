<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get id
$data = json_decode(file_get_contents("php://input"));

// Make sure id is not empty
if(!empty($data->id)){
    // Sanitize input
    $id = htmlspecialchars(strip_tags($data->id));
    
    // Query
    $query = "DELETE FROM employees WHERE id = :id";
    $stmt = $db->prepare($query);
    
    // Bind data
    $stmt->bindParam(":id", $id);
    
    // Execute query
    if($stmt->execute()){
        http_response_code(200);
        echo json_encode(array("message" => "Employee was deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete employee."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to delete employee. ID is required."));
}
