<?php
// Include files
include_once '../Config/Cors.php';
include_once '../Config/Database.php';

// Establish database connection
$datebaseService = new DatabaseService();
$connection = $datebaseService->getConnection();

try{

    // Get incoming data
    $data = json_decode(file_get_contents("php://input"));

    if(empty($data->name)){
        
        // Send error response
        http_response_code(500);
        echo json_encode([
            "message" => "Name can't be empty"
        ]);
    
        exit(0);
    }

    // Data fields
    $name = $data->name;

    // Prepare query
    $query = "
        INSERT INTO 
            lists
        SET 
            name = :name
        ";

    $statement = $connection->prepare($query);

    // Bind data
    $statement->bindParam(":name", $name);


    // Execute statement
    if($statement->execute()){
        
        // Send success response
        http_response_code(200);
        echo json_encode([
            "message" => $name . " was created as a new list"
        ]);
    }else {

        // Send error response
        http_response_code(500);
        echo json_encode([
            "message" => "Unable to create list"
        ]);
    }
} catch(\Exception $e) {

     // Send error response
     http_response_code(500);
     echo json_encode([
         "message" => $e
     ]);
}