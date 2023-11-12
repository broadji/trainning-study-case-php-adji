<?php

//How to use Methode Collection
use API\Connection;

include "db_connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
error_reporting(E_ERROR);

class Todo
{
    private $id;
    private $title;
    private $description;
    private $attachment;

    // Constructor
    public function __construct($id, $title, $description, $attachment)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->attachment = $attachment;
    }

     // JSON Serial
     public function jsonSerial()
     {
         return [
             'id' => $this->getId(),
             'title' => $this->getTitle(),
             'description' => $this->getDescription(),
             'attachment' => $this->getAttachment(),
         ];
     }

    // Getter methods
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAttachment()
    {
        return $this->attachment;
    }
}

class TodoCollection
{
    private $todos = [];

    // Add todo to the collection
    public function addTodo(Todo $todo)
    {
        $this->todos[] = $todo;
    }

    // Get all todos in the collection
    public function getAllTodos()
    {
        return $this->todos;
    }

    // Get a specific todo by ID
    public function getTodoById($id)
    {
        foreach ($this->todos as $todo) {
            if ($todo->getId() == $id) {
                return $todo;
            }
        }
        return null; // Todo not found
    }
}


if ($_SERVER['REQUEST_METHOD'] !== 'GET') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Reqeust Detected! Only get method is allowed',
    ]);
    exit;
endif;

$database = new Connection;
$conn = $database->dbConnection();
$id = null;


if (isset($_GET['id'])) {
    $todo_id = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 'all_todos',
            'min_range' => 1
        ]
    ]);
}

try {

   $todoCollection= new TodoCollection();
   $sql = is_numeric($todo_id) ? "SELECT * FROM `tasks` WHERE id='$todo_id'" : "SELECT * FROM `tasks`";
    

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    if ($stmt->rowCount() > 0) :

        $data = null;
        if (is_numeric($todo_id)) {

             $data = $stmt->fetch(PDO::FETCH_ASSOC);
             $todo= new Todo($data["id"],$data["title"],$data["description"],$data["attachment"]);
            
             $todoCollection->addTodo($todo);
        } else {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($data as $item){
                $todo = new Todo($item["id"],$item["title"],$item["description"],$item["attachment"]);
                $todoCollection->addTodo($todo);
            }
        }

        $listTodo = $todoCollection->getAllTodos();

        $data = [];
        foreach($listTodo as $list){
            array_push($data, $list->jsonSerial());
        }
        
        echo json_encode([
          'success' => 1,
          'data' => $data,
        ]);
        return json_encode([
           'success' => 1,
           'data' => $data,
        ]);

    else :
        echo json_encode([
            'success' => 0,
            'message' => 'No Record Found!',
        ]);
    endif;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
} 
?>