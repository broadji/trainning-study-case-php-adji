<?php
// How to use Method Magic 
use API\Connection;

include "db_connect.php";

class DeleteHandler {
    private $database;

    public function __construct() {
        
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->database = new Connection;
    }

    public function handleRequest() {

     
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == "OPTIONS") {
            die();
        }

        // if ($method !== 'DELETE') {
        //     $this->sendErrorResponse(405, 'Bad Request detected. HTTP method should be DELETE');
        // }

        $id = $_GET['id'];

        if (!isset($id)) {
            $this->sendErrorResponse(400, 'Please provide the post ID.');
        }

        try {
        
            $conn = $this->database->dbConnection();
            $this->deletePost($conn, $id);

        } catch (PDOException $e) {
            $this->sendErrorResponse(500, $e->getMessage());
        }
    }

    private function deletePost($conn, $id) {
        
        $fetch_post = "SELECT * FROM `tasks` WHERE id=:id";
        $fetch_stmt = $conn->prepare($fetch_post);
        $fetch_stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) {
            $delete_post = "DELETE FROM `tasks` WHERE id=:id";
            $delete_post_stmt = $conn->prepare($delete_post);
            $delete_post_stmt->bindValue(':id', $id, PDO::PARAM_INT);

            if ($delete_post_stmt->execute()) {
                $this->sendSuccessResponse('Record Deleted successfully');
            } else {
                $this->sendErrorResponse(500, 'Could not delete. Something went wrong.');
            }
        } else {
            $this->sendErrorResponse(404, 'Invalid ID. No posts found by the ID.');
        }
    }

    private function sendSuccessResponse($message) {

        echo json_encode(['success' => 1, 'message' => $message]);
        exit;
    }

    private function sendErrorResponse($code, $message) {
        http_response_code($code);
        echo json_encode(['success' => 0, 'message' => $message]);
        exit;
    }
}

// Instantiate and handle the request
$deleteHandler = new DeleteHandler();
$deleteHandler->handleRequest();




// use API\Connection;

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: access");
// header("Access-Control-Allow-Methods: DELETE");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



// $method = $_SERVER['REQUEST_METHOD'];

// if ($method == "OPTIONS") {
//     die();
// }


// if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') :
//     http_response_code(405);
//     echo json_encode([
//         'success' => 0,
//         'message' => 'Bad Reqeust detected. HTTP method should be DELETE',
//     ]);
//     exit;
// endif;

// require 'db_connect.php';
// $database = new Connection;
// $conn = $database->dbConnection();

// $data = json_decode(file_get_contents("php://input"));
// //echo $data = file_get_contents("php://input");

// $id =  $_GET['id'];



// if (!isset($id)) {
//     echo json_encode(['success' => 0, 'message' => 'Please provide the post ID.']);
//     exit;
// }

// try {

//     $fetch_post = "SELECT * FROM `tasks` WHERE id=:id";
//     $fetch_stmt = $conn->prepare($fetch_post);
//     $fetch_stmt->bindValue(':id', $id, PDO::PARAM_INT);
//     $fetch_stmt->execute();

//     if ($fetch_stmt->rowCount() > 0) :

//         $delete_post = "DELETE FROM `tasks` WHERE id=:id";
//         $delete_post_stmt = $conn->prepare($delete_post);
//         $delete_post_stmt->bindValue(':id', $id,PDO::PARAM_INT);

//         if ($delete_post_stmt->execute()) {

//             echo json_encode([
//                 'success' => 1,
//                 'message' => 'Record Deleted successfully'
//             ]);
//             exit;
//         }

//         echo json_encode([
//             'success' => 0,
//             'message' => 'Could not delete. Something went wrong.'
//         ]);
//         exit;

//     else :
//         echo json_encode(['success' => 0, 'message' => 'Invalid ID. No posts found by the ID.']);
//         exit;
//     endif;

// } catch (PDOException $e) {
//     http_response_code(500);
//     echo json_encode([
//         'success' => 0,
//         'message' => $e->getMessage()
//     ]);
//     exit;
// }



?>