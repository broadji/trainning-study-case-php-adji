<?php
// use API\Connection;

// include "db_connect.php";

// $database = new Connection;
// $conn = $database->dbConnection();
// $id = null;

//HTTP auth
include "auth/auth.php";

if (isset($_COOKIE['user'])) {
    $username = $_COOKIE['user'];
} else {
    header('Location: index.php');
    exit();
}


$curl = curl_init();

curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost/assignmentphp/API/list_todo.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;

$datajson = json_decode($response, true);
$dataArray = $datajson["data"];
// $tasks = [
//     ['id' => 1, 'title' => 'Mengerjakan Tugas 1', 'description' => 'Deskripsi tugas 1'],
//     ['id' => 2, 'title' => 'Belajar PHP', 'description' => 'Mempelajari PHP'],
//     ['id' => 3, 'title' => 'Beli Bahan Makanan', 'description' => 'Daftar belanja'],
// ];



?>

<!DOCTYPE html>
<html>
<head>
    <title>My To-Do List</title>
    <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .edit-mode input {
      width: 100%;
      pointer-events: none; /* Disable input events when not in edit mode */
    }

    .form-disable[disabled] {
      display: none; /* Hide the input when disabled */
    }

    .submit-button[disabled] {
      display: none; /* Hide the button when disabled */
    }
  </style>
</head>
<body>
    <h1>Welcome, <?php echo $_COOKIE['user']; ?>!</h1>
    <form method="post" action="logout.php">
        <button type="submit" name="logout">Logout</button>
    </form>
    <br>  <br>  <br>
    <h1>Daftar Tugas</h1>
    <form action="API/create_todo.php"  method="post"  enctype="multipart/form-data">
    <table>
    
    <tr><td>Judul Tugas:</td><td> <input type="title" name="title"/></td></tr>  
    <tr><td>Description:</td><td> <textarea type="description" name="description"></textarea></td></tr>
    <!-- <tr><td>Attachment:</td><td> <input type="attachment" name="attachment"/></td></tr>   -->
    <td>Select image to upload:</td> 
    <td><input type="file" name="attachment" id="attachment"> </td>   
    <tr><td colspan="2"><input type="submit" value="Submit", name="submit"/>  </td></tr>   
    </table>
    </form>
    <br>  <br>  <br>

  <br>

<form method="post" action="API/update_todo.php"  enctype="multipart/form-data">
<table border="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Todo</th>
        <th>Description</th>
        <th>Attachment</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($dataArray as $data): ?>
        <tr>
        <td><?php echo $data['id']; ?></td>
        
          <!-- Handling Security XSSFilter-->
          <input type="hidden" name="id" id="id_<?php echo $data['id']; ?>" value="<?php echo htmlspecialchars($data['id']); ?>">
          <td class="edit-mode"><input type="text" name="title_<?php echo $data['id']; ?>" value="<?php echo htmlspecialchars($data['title']); ?>"></td>
          <td class="edit-mode"><input type="text" name="description_<?php echo $data['id']; ?>" value="<?php echo htmlspecialchars($data['description']); ?>"></td>
          <td class="form-disable">
            <input type="file" name="attachment" value="<?php echo $data['attachment']; ?>" disabled>
          </td>
          <td>
            <button type="button" onclick="toggleEditMode(this, <?php echo $data['id']; ?>)" >Edit</button>
                    <button href="#" onclick="deleteTask(<?php echo $data['id']; ?>); return false;">Delete</button>

            <input type="submit" class="submit-button" name="submit_<?php echo $data['id']; ?>" value="Submit" disabled>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</form>
</table>
    

<script>
function deleteTask(taskId) {
    // Confirm deletion (you may customize this part)
    if (confirm("Are you sure you want to delete this task?")) {
        // Perform the DELETE request using Fetch API
        fetch(`API/delete_todo.php?id=${taskId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
                // Add other headers if needed
            },
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response, you may redirect or update the UI as needed
            console.log(data);
            // Reload the page or update the UI
            location.reload();
        })
        .catch(error => {
            // Handle errors
            console.error('Error:', error);
        });
    }
}

function toggleEditMode(button, id) {
    const submitButton = button.parentNode.querySelector('.submit-button');
    submitButton.removeAttribute('disabled');

    const row = button.parentNode.parentNode;
    const editModeElements = row.querySelectorAll('.edit-mode');
    const fileInput = row.querySelector('.form-disable input[type="file"]');
   
    const inputID = document.getElementsByName("id");

    for(let i= 0; i<inputID.length; i++){
        inputID[i].setAttribute("value", id);
    }

    for (let i = 0; i < editModeElements.length; i++) {
      const element = editModeElements[i];
      element.classList.toggle('edit-mode');
    }

    // Enable the file input
    fileInput.removeAttribute('disabled');
  }
</script>
</body>
</html>
