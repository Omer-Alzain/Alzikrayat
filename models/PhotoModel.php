<?php
require_once __DIR__ . '/../core/Model.php';
class PhotoModel extends Model
{
    public function getAllPhotos()
    {
        $stmt = $this->db->prepare("SELECT * FROM Photos");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPhotoById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Photos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}
