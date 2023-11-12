<?php

// How to use Methode Interface & Abstract
namespace API;
use API\Connection;
use PDO;
use PDOException;

include "db_connect.php";

interface TaskHandlerInterface
{
    public function handleRequest();
    public function respond($statusCode, $message);
}

class TaskHandler implements TaskHandlerInterface
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

        $id = $_POST["id"];
        $title = $_POST["title_$id"];
        $description = $_POST["description_$id"];
        $attachment = $this->handleFileUpload();

        if (!$title || !$description || !$attachment) {
            $this->respond(400, 'Please enter mandatory fields: title, description, and attachment');
        }

        $this->insertTask($id, $title, $description, $attachment);
    }

    private function handleFileUpload()
    {
        {
            $targetDir = "uploads/";
    
    
            // File handling
            $target_dir = "uploads/";
            $uploadOk = 1;
            $attachmentPOST = $target_dir . basename($_FILES["attachment"]["name"]);
            $imageFileType = strtolower(pathinfo($attachmentPOST,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if(isset($_FILES["attachment"]["name"])) {
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
                    if ($_FILES["attachmen"]["size"] > 500000) {
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
    }

    private function insertTask($id, $title, $description, $attachment)
    {
        try {
            $query = "UPDATE tasks SET title = :title, description = :description, attachment = :attachment WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            // Handling Security SQL Injection
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':attachment', $attachment, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $this->respond(201, 'Data Updated Successfully.');
            }

            $this->respond(500, 'There is some problem in data updating');
        } catch (PDOException $e) {
            $this->respond(500, $e->getMessage());
        }
    }

    public function respond($statusCode, $message)
    {
        http_response_code($statusCode);
        echo json_encode(['success' => $statusCode === 201, 'message' => $message]);
        exit;
    }
}

$taskHandler = new TaskHandler();
$taskHandler->handleRequest();

?>
