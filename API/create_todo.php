<?php

// How to use Overring Method
use API\Connection;

include "db_connect.php";

class TaskHandler
{
    private $conn;

    public function __construct()
    {
        $database = new Connection();
        $this->conn = $database->dbConnection();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == "OPTIONS") {
            die();
        }

        if ($method !== 'POST') {
            $this->respond(405, 'Bad Request! Only POST method is allowed');
        }

        $data = json_decode(file_get_contents("php://input"));

        $title = $_POST["title"];
        $description = $_POST["description"];
        $attachment = $this->handleFileUpload();

        if (!$title || !$description || !$attachment) {
            $this->respond(400, 'Please enter mandatory fields: title, description, and attachment');
        }

        $this->insertTask($title, $description, $attachment);
    }

    private function handleFileUpload()
    {
        $targetDir = "uploads/";
       
        // File handling
        $target_dir = "uploads/";
        $uploadOk = 1;
        
        $attachmentPOST = $target_dir . basename($_FILES["attachment"]["name"]);
        $imageFileType = strtolower(pathinfo($attachmentPOST,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
             $check = getimagesize($_FILES["attachment"]["tmp_name"]);
               if($check !== false) {
                 // echo "File is an image - " . $check["mime"] . ".";
                   $uploadOk = 1;
                 } else {
                    $uploadOk = 0;
                    echo json_encode([
                        'success' => 0,
                        'message' => 'File is not an image.',
                    ]);
                    exit;
     }
     // Check file size
     if ($_FILES["attachment"]["size"] > 500000) {
        $uploadOk = 0;
        echo json_encode([
        'success' => 0,
        'message' => 'Sorry, your file is too large.',
     ]);
     exit;
   }
   
   // Allow certain file formats
   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF" ){
     
     $uploadOk = 0;
     echo json_encode([
         'success' => 0,
         'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.',
     ]);
     exit;
   }

   if($uploadOk==1){
    //return file
    $attachment = $targetDir . basename($_FILES["attachment"]["name"]);
    return $attachment;
   } else {
    echo json_encode([
        'success' => 0,
        'message' => 'File not uploded.',
    ]);
    exit;
   }
 }
 

       
    }

    private function insertTask($title, $description, $attachment)
    {
        try {
            $query = "INSERT INTO `tasks` (title, description, attachment) VALUES (:title, :description, :attachment)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':attachment', $attachment, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $this->respond(201, 'Data Inserted Successfully.');
            }

            $this->respond(500, 'There is some problem in data inserting');
        } catch (PDOException $e) {
            $this->respond(500, $e->getMessage());
        }
    }

    private function respond($statusCode, $message)
    {
        http_response_code($statusCode);
        echo json_encode(['success' => $statusCode === 201, 'message' => $message]);
        exit;
    }
}

$taskHandler = new TaskHandler();
$taskHandler->handleRequest();




// use API\Connection;

// include "db_connect.php";
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: access");
// header("Access-Control-Allow-Methods: POST");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

// $method = $_SERVER['REQUEST_METHOD'];

// if ($method == "OPTIONS") {
//     die();
// }

 
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') :
//     http_response_code(405);
//     echo json_encode([
//         'success' => 0,
//         'message' => 'Bad Request!.Only POST method is allowed',
//     ]);
//     exit;
// endif;
 

// $database = new Connection;
// $conn = $database->dbConnection();
 
// $data = json_decode(file_get_contents("php://input"));


// $titlePOST = $_POST["title"];
// $descriptionPOST = $_POST["description"];
// // $attachmentPOST = $_POST["attachment"]; 

//  // File handling
//  $targetDir = "uploads/"; // Specify your upload directory
// //  $attachmentPOST = $targetDir . basename($_FILES["attachment"]["name"]);
// //  move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachment);


// $target_dir = "uploads/";
// $uploadOk = 1;
// $attachmentPOST = $target_dir . basename($_FILES["attachment"]["name"]);
// $imageFileType = strtolower(pathinfo($attachmentPOST,PATHINFO_EXTENSION));
// // Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["attachment"]["tmp_name"]);
//     if($check !== false) {
//         // echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         $uploadOk = 0;
//     echo json_encode([
//         'success' => 0,
//         'message' => 'File is not an image.',
//     ]);
//     exit;
//     }

// // Check file size
// if ($_FILES["attachment"]["size"] > 500000) {
//     $uploadOk = 0;
//     echo json_encode([
//         'success' => 0,
//         'message' => 'Sorry, your file is too large.',
//     ]);
//     exit;
//   }
  
//   // Allow certain file formats
//   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF" ){
    
//     $uploadOk = 0;
//     echo json_encode([
//         'success' => 0,
//         'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.',
//     ]);
//     exit;
//   }
// }

// if (!isset($titlePOST) || !isset($descriptionPOST) || !isset($attachmentPOST)) :
 
//     echo json_encode([
//         'success' => 0,
//         'message' => 'Please enter mandatory fileds |  title , description and attachment',
//     ]);
//     exit;
 
// elseif (empty(trim($titlePOST)) || empty(trim($descriptionPOST)) || empty(trim($attachmentPOST))) :
 
//     echo json_encode([
//         'success' => 0,
//         'message' => 'Field cannot be empty. Please fill all the fields.',
//     ]);
//     exit;
 
// endif;
 
// try {
 
//     $title = $titlePOST;
//     $description = $descriptionPOST;
//     $attachment = $attachmentPOST;
//     // $password = htmlspecialchars(trim($data->password));
//     // $gender = $data->gender;
//     // $hobbies = $hobbies_list;
//     // $country = $data->country;
 
//     $query = "INSERT INTO `tasks` (title, description, attachment) VALUES (:title, :description, :attachment)";
 
//     $stmt = $conn->prepare($query);
 
//     $stmt->bindValue(':title', $title, PDO::PARAM_STR);
//     $stmt->bindValue(':description', $description, PDO::PARAM_STR);
//     $stmt->bindValue(':attachment', $attachment, PDO::PARAM_STR);
    

//     if ($stmt->execute()) {
 
//         http_response_code(201);
//         echo json_encode([
//             'success' => 1,
//             'message' => 'Data Inserted Successfully.'
//         ]);
//         exit;
//     }
    
//     echo json_encode([
//         'success' => 0,
//         'message' => 'There is some problem in data inserting'
//     ]);
//     exit;
 
// } catch (PDOException $e) {
//     http_response_code(500);
//     echo json_encode([
//         'success' => 0,
//         'message' => $e->getMessage()
//     ]);
//     exit;
// }


?>