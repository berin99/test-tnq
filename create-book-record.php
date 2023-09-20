<?php 
use Books\Book;
require_once './Model/Book.php';
$book = new Book();
if (isset($_POST["addBook"])) {
    $saveResponse = $book->saveBookRecord();
}
$categoryArray=[];
$categoryArray = $book->getCategoryMaster();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Book Record</title>
</head>
<body>
    <h1>Create Book Record</h1>
    <a href="index.php"><button>Home</button></a>
    <a href="show-books.php"><button>Books List</button></a><br><br>
<?php 
if(!empty($saveResponse["status"]))
{
  if($saveResponse["status"] == "error")
  {
?>
    <p style = "color:red"><strong>Error!</strong> <?php echo $saveResponse["message"]; ?></p>
<?php 
  } 
  else if($saveResponse["status"] == "success")
  {
?>
    <p style = "color:green"><strong>Success!</strong> <?php echo $saveResponse["message"]; ?></p>
<?php 
  }
}
?>  
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required maxlength="100"><br><br>
        <label for="price">Price (5 - 1000):</label>
        <input type="number" name="price" required min="5" max="1000"><br><br>
        <label for="description">Description:</label>
        <textarea name="description"></textarea><br><br>
        <label for="category">Category:</label>
        <select name="category_id[]" multiple required>
<?php
foreach ($categoryArray as $key => $value) {
?>
          <option value="<?php echo $value['id'] ?>"><?php echo $value['name']; ?></option>
<?php
}
?>
        </select><br><br>
        <label for="image">Image Upload (jpg or png):</label>
        <input type="file" name="image" accept="image/jpeg, image/png" required><br><br>
        <input type="submit" name="addBook" value="Create Book">
    </form>
</body>
</html>