<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../core/Session.php';

class CommentController extends Controller{
    private $CommentModel;

    public function __construct()
    {
        $this->CommentModel = new CommentModel();
    }

    public function getCommentsOnPhoto($photoId)
    {
        $data = $this->CommentModel->getCommentsByPhotoId($photoId);
        if ($data === false) {
            // Handle the case where no comments are found
            $data = [];
        }
        return $data;
    }
    public function createComment()
    {
        $errors = [];
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'photo_id' => $_POST['photo_id'] ?? '',
                'user_id' => $_POST['user_id'] ?? '',
                'comment' => $_POST['comment'] ?? '',
            ];
            $commentId = $this->CommentModel->createComment($data);
            if ($commentId) {
                // Comment created successfully
                header('Location: /photo/' . $data['photo_id']);
                exit;
            } else {
                // Handle error in comment creation
                $_SESSION['errors'] = ['comment' => ['error creating comment.']];
                header('Location: /photo/'.$data['photo_id'] . '/comment');
                
            }
        }
    }
    public function delete($commentId , $photoId){
        $errors = [];
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        $deleteSucsses = $this->CommentModel->deleteComment($commentId , Session::getCurrentUserId());
        if(!$deleteSucsses){
            $_SESSION['errors'] = ['comment' => ['error deleting comment.']];
            header('Location: /photo/'.$photoId. '/comment');
            exit;
        }
        header('Location: /photo/'.$photoId);
    }
}