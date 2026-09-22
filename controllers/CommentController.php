<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Validator.php';

class CommentController extends Controller{
    private $commentModel;

    public function __construct()
    {
        $this->commentModel = new commentModel();
    }

    public function getCommentsOnPhoto($photoId)
    {
        $data = $this->commentModel->getCommentsByPhotoId($photoId);
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
                'user_id' => Session::getCurrentUserId() ?? '',
                'comment' => $_POST['comment'] ?? '',
            ];
            $rules = [
                'comment' => ['required']
            ];

            $errors = Validator::validate($data, $rules);
            if (empty($errors)) {
                $commentId = $this->commentModel->createComment($data);
                if ($commentId) {
                    // Comment created successfully
                    header('Location: /photo/' . $data['photo_id']);
                    exit;
                } else {
                    // Handle error in comment creation
                    $_SESSION['errors'] = ['comment' => ['error creating comment.']];
                    header('Location: /photo/'.$data['photo_id']);
                    exit;
                }
            }
            $_SESSION['errors'] = $errors;
        }
    }
    public function delete($commentId , $photoId){
        $errors = [];
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        $deleteSucsses = $this->commentModel->deleteComment($commentId , Session::getCurrentUserId());
        if(!$deleteSucsses){
            $_SESSION['errors'] = ['comment' => ['error deleting comment.']];
            header('Location: /photo/'.$photoId);
            exit;
        }
        header('Location: /photo/'.$photoId);
        exit;
    }
}