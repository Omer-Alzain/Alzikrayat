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

    public function createPhoto($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Photos (user_id, title, description, file_path, created_at)
             VALUES (:user_id, :title, :description, :file_path, NOW())"
        );
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':file_path' => $data['file_path']
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function deletePhoto(int $photoId, int $userId): bool
    {
        // First, check if the photo belongs to the user
        $stmt = $this->db->prepare("SELECT * FROM Photos WHERE id = :photoId AND user_id = :userId");
        $stmt->execute([':photoId' => $photoId, ':userId' => $userId]);
        $photo = $stmt->fetch();

        if (!$photo) {
            return false; // Photo does not belong to the user or does not exist
        }

        // Proceed to delete the photo
        $stmt = $this->db->prepare("DELETE FROM Photos WHERE id = :photoId");
        return $stmt->execute([':photoId' => $photoId]);
    }
    
}
