<?php
// Include files
include_once '../Config/Cors.php';
include_once '../Config/Database.php';
require "../../vendor/autoload.php";

use Firebase\JWT\JWT;

function login($email, $password){

    // Establish database connection
    $datebaseService = new DatabaseService();
    $connection = $datebaseService->getConnection();

    try{
        
        // Prepare query
        $query = "
            SELECT 
                users_id, name, password 
            FROM 
                users
            WHERE 
                email = :email 
            LIMIT 1";

        $statement = $connection->prepare($query);
        $statement->bindParam("email", $email);
        $statement->execute();


        $num = $statement->rowCount();

        if ($num > 0) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            $id = base64_encode($row['users_id']);
            $name = $row['name'];
            $password2 = $row['password'];

            if (password_verify($password, $password2)) {
                $secret_key = "todo-app-key";
                $issuer_claim = "THE_ISSUER"; // this can be the servername
                $audience_claim = "THE_AUDIENCE";
                $issuedat_claim = time(); // issued at
                $notbefore_claim = $issuedat_claim; //not before in seconds
                $expire_claim = $issuedat_claim + 3600; // expire time in seconds
                $token = array(
                    "iss" => $issuer_claim,
                    "aud" => $audience_claim,
                    "iat" => $issuedat_claim,
                    "nbf" => $notbefore_claim,
                    "exp" => $expire_claim,
                    "data" => array(
                        "id" => $id,
                        "name" => $name,
                        "email" => $email
                    )
                );

                $jwt = JWT::encode($token, $secret_key, 'HS256');
                echo json_encode(
                    array(
                        "message" => "Successful login",
                        "code" => 200,
                        "jwt" => $jwt,
                        "email" => $email,
                        "name" => $name,
                        "id" => $id,
                        "expireAt" => $expire_claim
                    )
                );
            } else {
                echo json_encode(array("message" => "Wrong password", "code" => 500));
            }
        } else {
            echo json_encode(array("message" => "Login failed", "code" => 401));
        }
    }
    catch(\Exception $e) {

        // Send error response
        echo json_encode([
            "message" => $e,
            "code" => 500
        ]);
    }
}