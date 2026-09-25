<?php
//import nedded file.
require_once __DIR__ . '/../core/Model.php';
/**
 * class for controlling the database opration for photos.
 * has 4 function to creat a photo,fetch photo by id and for fetching all photos and delet a photo.
 */
class PhotoModel extends Model
{
    /**
     * fetching all photos
     * Params:$stmt to store the prepared query and excute it and store the result.
     * return array of photos.
    */ 
    public function getAllPhotos()
    {
        $stmt = $this->db->prepare("SELECT * FROM Photos");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * fetching a photo
     * Params:$stmt to store the prepared query and excute it and store the result.
     * return a photo.
    */ 
    public function getPhotoById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Photos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * storing new photo data 
     * Params:$stmt to store the prepared query and excute it.
     * return the id of the new photo.
    */ 
    public function createPhoto($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Photos (user_id, file_name, title, description)
             VALUES (:user_id, :file_name, :title, :description)"
        );
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':file_name' => $data['file_name'],
            ':title' => $data['title'],
            ':description' => $data['description']
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * deleting a photo
     * Params:$stmt to store the prepared query and excute it.
     * $photo store the result of the fetch.
     * return boolien.
    */ 
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
