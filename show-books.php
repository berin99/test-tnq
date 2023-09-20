<?php 
use Books\Book;
require_once './Model/Book.php';
$bookObj = new Book();
$books=[];
$books = $bookObj->getBooksList();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Records</title>
</head>
<body>
    <h1>Book Records</h1>
    <a href="index.php"><button>Home</button></a>
    <a href="create-book-record.php"><button>Add New Book</button></a><br><br>
    <?php if (!empty($books)): ?>
        <table border="1" width = "80%">
          <tr>
              <th>Title</th>
              <th>Price</th>
              <th>Description</th>
              <th>Category</th>
              <th>Image</th>
          </tr>
          <?php foreach ($books as $book): ?>
            <tr>
              <td><?php echo $book['title']; ?></td>
              <td><?php echo $book['price']; ?></td>
              <td><?php echo $book['description']; ?></td>
              <td><?php echo $bookObj->getCategorName($book['category_id']); ?></td>
              <td><img src="<?php echo $book['image_path']; ?>" width="100"></td>
            </tr>
          <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No records found.</p>
    <?php endif; ?>
</body>
</html>