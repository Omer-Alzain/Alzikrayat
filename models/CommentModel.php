<?php
require_once __DIR__ . '/../core/Model.php';
class CommentModel extends Model
{
    public function createComment($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Comments (photo_id, user_id, comment)
             VALUES (:photo_id, :user_id, :comment)"
        );
        $stmt->execute([
            ':photo_id' => $data['photo_id'],
            ':user_id' => $data['user_id'],
            ':comment' => $data['comment'],
        ]);
        return (int) $this->db->lastInsertId();
    }
    public function getCommentsByPhotoId($photoId)
    {
        $stmt = $this->db->prepare("SELECT Comments.id, Comments.comment, Comments.date_time,Comments.user_id, Users.first_name, Users.last_name FROM Comments JOIN Users ON Comments.user_id = Users.id WHERE Comments.photo_id = :photoId ORDER BY Comments.date_time ASC");
        $stmt->execute([':photoId' => $photoId]);
        return $stmt->fetchAll();
    }
    public function deleteComment(int $commentId, int $userId): bool
    {
        // First, check if the comment belongs to the user
        $stmt = $this->db->prepare("SELECT * FROM Comments WHERE id = :commentId AND user_id = :userId");
        $stmt->execute([':commentId' => $commentId, ':userId' => $userId]);
        $comment = $stmt->fetch();

        if (!$comment) {
            return false; // Comment does not belong to the user or does not exist
        }

        // Proceed to delete the comment
        $stmt = $this->db->prepare("DELETE FROM Comments WHERE id = :commentId");
        return $stmt->execute([':commentId' => $commentId]);
    }
    

}