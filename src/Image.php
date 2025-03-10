<?php
require_once 'Database.php';

class Image {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllImages() {
        $mysqli = $this->db->mysqli;
        $sql = "SELECT * FROM Img ORDER BY id DESC";
        $result = mysqli_query($mysqli, $sql);

        if (!$result) {
            die("Error fetching images: " . mysqli_error($mysqli));
        }

        $images = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $images[] = $row;
        }

        return $images;
    }

    public function deleteImage($id) {
        $mysqli = $this->db->mysqli;
        $sql = "DELETE FROM Img WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->errno) {
            die("Error deleting image: " . $stmt->error);
        }
        $stmt->close();

        header('Location: Galerie.php');
        exit;
    }

    public function editImage($id, $imageName, $imageData, $mime) {
        $mysqli = $this->db->mysqli;
        // Prepare the SQL statement with placeholders
        $sql = "UPDATE Img SET name = ?, data = ?, mime = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);

        // Bind the parameters and execute the statement
        $stmt->bind_param("sssi", $imageName, $imageData, $mime, $id);
        $result = $stmt->execute();

        if (!$result) {
            die("Error editing image: " . $stmt->error);
        }

        // Close the statement
        $stmt->close();

        header('Location: Galerie.php');
        exit;
    }

    public function uploadImage($imageName, $imageData, $mime) {
        $mysqli = $this->db->mysqli;
    
        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO Img (name, data, mime) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
    
        // Bind the parameters and execute the statement
        $stmt->bind_param("sss", $imageName, $imageData, $mime);
        $result = $stmt->execute();
    
        if (!$result) {
            die("Error uploading image: " . $stmt->error);
        }
    
        // Close the statement
        $stmt->close();
    
        header('Location: Galerie.php');
        exit;
    }

    public function getImageDetails($id) {
        $mysqli = $this->db->mysqli;
        $sql = "SELECT * FROM Img WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result) {
            die("Error fetching image details: " . $stmt->error);
        }

        return $result->fetch_array(MYSQLI_ASSOC);
    }
}