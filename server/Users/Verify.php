<?php
// Include files
include_once '../Config/Cors.php';
include_once '../Config/Database.php';
require "../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Establish database connection
$datebaseService = new DatabaseService();
$connection = $datebaseService->getConnection();

// Get incoming data
$data = json_decode(file_get_contents("php://input"));
$secret_key = "todo-app-key";


    $jwt = $_SERVER['HTTP_AUTHORIZATION'];

    if ($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            echo json_encode(array(
                "message" => "Access granted",
                "code" => 200
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                "message" => "Access denied.",
                "code" => 403,
                "error" => $e->getMessage(),
                "token" => $jwt,
            ));
        }
    } else {
    
        echo json_encode(array(
            "message" => "Something went wrong",
            "code" => 500,
        ));
    }