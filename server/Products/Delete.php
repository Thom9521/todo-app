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

    if(empty($data->products_id)){
        
        // Send error response
        http_response_code(500);
        echo json_encode([
            "message" => "products_id can't be empty"
        ]);
    
        exit(0);
    }

    // Data fields
    $products_id = $data->products_id;

    // Prepare query
    $query = "
        DELETE FROM 
            products
        WHERE 
            products_id = :products_id
        ";

    $statement = $connection->prepare($query);

    // Bind data
    $statement->bindParam(":products_id", $products_id);


    // Execute statement
    if($statement->execute()){
        
        // Send success response
        http_response_code(200);
        echo json_encode([
            "message" => "Success"
        ]);
    }else {

        // Send error response
        http_response_code(500);
        echo json_encode([
            "message" => "Unable to delete product"
        ]);
    }
} catch(\Exception $e) {

     // Send error response
     http_response_code(500);
     echo json_encode([
         "message" => $e
     ]);
}