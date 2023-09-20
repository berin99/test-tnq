<?php
namespace Books;
session_start();
class Book
{
    public $ds;

    function __construct()
    {
        require_once __DIR__ . './../lib/DataSource.php';
        $this->ds = new DataSource();
    }

    public function saveBookRecord()
    {
        $response = array();

        // Validate title (mandatory, maximum length 100)
        if (empty($_POST["title"])) {
            $response = array("status" => "error", "message" => "Title is required.");
            return $response;
        } elseif (strlen($_POST["title"]) > 100) {
            $response = array("status" => "error", "message" => "Title should be at most 100 characters.");
            return $response;
        }
        // Validate at least one category selected
        if (empty($_POST["category_id"])) {
            $response = array("status" => "error", "message" => "At least one category is required.");
            return $response;
        }
        // Validate file upload (jpg or png)
        if (isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"])) {
            $allowedExtensions = array("jpg", "jpeg", "png");
            $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $response = array("status" => "error", "message" => "Only JPG or PNG files are allowed for image upload.");
                return $response;
            }
        }
        // Validate price (numeric, minimum 5, maximum 1000)
        if (!is_numeric($_POST["price"])) {
            $response = array("status" => "error", "message" => "Price should be a numeric value.");
            return $response;
        } elseif ($_POST["price"] < 5 || $_POST["price"] > 1000) {
            $response = array("status" => "error", "message" => "Price should be between 5 and 1000.");
            return $response;
        }
        
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["name"])) {
            $imagePath = isset($_FILES["image"]["name"]) ? $_FILES["image"]["name"] : "Undefined";
            $targetPath = "uploads/";
            $imagePath = $targetPath . $imagePath;
            $tempFile = $_FILES['image']['tmp_name'];
            $fileName = $_FILES["image"]["name"];

            $targetFileImage = $targetPath . $_FILES['image']['name'];

            if (move_uploaded_file($tempFile, $targetFileImage)) {
              $imageUpload = "true";
            } else {
              $imageUpload = "false";
            }
        }
        $query = 'INSERT INTO books (title, description, price, image_path, category_id) VALUES (?, ?, ?, ?, ?)';
        $paramType = 'sssss';
        $paramValue = array(
            $_POST["title"],
            $_POST["description"],
            $_POST["price"],
            $targetFileImage,
            implode(',', $_POST["category_id"])
        );

        $bookInsID = $this->ds->insert($query, $paramType, $paramValue);
        if(!empty($bookInsID)) {
            $response = array("status" => "success", "message" => "Book Added successfully.");

        }
        return $response;
    }

    public function getBooksList()
    {
        $query = 'SELECT title, description, price, image_path, category_id FROM books where status = ?';
        $paramType = 's';
        $paramValue = array(1);
        $booksListArr = $this->ds->select($query, $paramType, $paramValue);
        return $booksListArr;
    }

    public function getCategoryMaster()
    {
        $query = 'SELECT id,name FROM categories where status = ?';
        $paramType = 's';
        $paramValue = array(1);
        $categoryArr = $this->ds->select($query, $paramType, $paramValue);
        return $categoryArr;
    }

    public function getCategorName($catIDs)
    {
        $query = 'SELECT name FROM categories WHERE id IN('.$catIDs.') AND status = ?';
        $paramType = 's';
        $paramValue = array(1);
        $categoryArr = $this->ds->select($query, $paramType, $paramValue);
        $flattenedArray = array_map(function($subArray) {
          return implode(', ', $subArray);
        }, $categoryArr);
        $implodedString = implode(', ', $flattenedArray);
        return $implodedString;
    }
}