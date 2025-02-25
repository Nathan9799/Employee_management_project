<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->position) &&
    !empty($data->salary) &&
    !empty($data->date_joined)
){
    $name = htmlspecialchars(strip_tags($data->name));
    $email = htmlspecialchars(strip_tags($data->email));
    $position = htmlspecialchars(strip_tags($data->position));
    $salary = htmlspecialchars(strip_tags($data->salary));
    $date_joined = htmlspecialchars(strip_tags($data->date_joined));
    
    $query = "INSERT INTO employees (name, email, position, salary, date_joined) VALUES (:name, :email, :position, :salary, :date_joined)";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":position", $position);
    $stmt->bindParam(":salary", $salary);
    $stmt->bindParam(":date_joined", $date_joined);
    
    if($stmt->execute()){
        http_response_code(201); 
        echo json_encode(array("message" => "Employee was created.", "id" => $db->lastInsertId()));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create employee."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create employee. Data is incomplete."));
}
?>
