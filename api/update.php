<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Make sure id is not empty
if(!empty($data->id)){
    // Sanitize input
    $id = htmlspecialchars(strip_tags($data->id));
    $name = htmlspecialchars(strip_tags($data->name));
    $email = htmlspecialchars(strip_tags($data->email));
    $position = htmlspecialchars(strip_tags($data->position));
    $salary = htmlspecialchars(strip_tags($data->salary));
    $date_joined = htmlspecialchars(strip_tags($data->date_joined));
    
    // Query
    $query = "UPDATE employees SET 
              name = :name,
              email = :email,
              position = :position,
              salary = :salary,
              date_joined = :date_joined
              WHERE id = :id";
              
    $stmt = $db->prepare($query);
    
    // Bind data
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":position", $position);
    $stmt->bindParam(":salary", $salary);
    $stmt->bindParam(":date_joined", $date_joined);
    
    // Execute query
    if($stmt->execute()){
        http_response_code(200);
        echo json_encode(array("message" => "Employee was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update employee."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update employee. ID is required."));
}
