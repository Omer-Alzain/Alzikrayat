<?php
//importing all the classes that will be nedded here 
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Validator.php';
//declaration of the class which use the core controller function
//param $commentModel that comunicate with the database
//it has 3 functions:__construct,getCommentsOnPhoto,and delete.
class CommentController extends Controller{
    private $commentModel;
    // creat object of the comment model
    public function __construct()
    {
        $this->commentModel = new commentModel();
    }

    
    /** 
     * handling the logic of getting comments on a photo.
     * params:$data for storing the comments 
     * return an array of arrays
    */
    public function getCommentsOnPhoto($photoId)
    {
        //fetching comments
        $data = $this->commentModel->getCommentsByPhotoId($photoId);
        if ($data === false) {
            // Handle the case where no comments are found
            $data = [];
        }
        return $data;
    }

    /**
     * handling the creation of comment and store it .
     * params:
     * $errors to store any kind of errors from empty field to errors of the database
     * $data,$rules : for storing data and their rules to be passed to a validator.
     * $commentId:store comment id for checking of success creation for now could be usful for future feature.
     * return either errors to the UI or redirect to the page with the new comment.
    */
    public function createComment()
    {
        $errors = [];
        //check authintication : if the user is not logged in so it redirect the user to the login.
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        //check if the request is post to proced with the creation of a comment
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //store the neccery data .
            $data = [
                'photo_id' => $_POST['photo_id'] ?? '',
                'user_id' => Session::getCurrentUserId() ?? '',
                'comment' => $_POST['comment'] ?? '',
            ];
            //rule to validate upon them.
            $rules = [
                'comment' => ['required']
            ];

            // call the validator with the data and store the errors .
            $errors = Validator::validate($data, $rules);
            //check for errors
            if (empty($errors)) {
                //creat the comment and store it is id for checking of success creation.
                $commentId = $this->commentModel->createComment($data);
                if ($commentId) {
                    // Comment created successfully
                    header('Location: /photo/' . $data['photo_id']);
                    exit;
                } else {
                    // Handle error in comment creation
                    $_SESSION['errors'] = ['comment' => ['error creating comment.']];
                    header('Location: /photo/'.$data['photo_id']);
                    unset($_SESSION['errors']);
                    exit;
                }
            }
            //if there is errors redirect to the photo and show errors
            $_SESSION['errors'] = $errors;
            header('Location: /photo/' . $data['photo_id']);
            unset($_SESSION['errors']);
            exit;
        }
    }

    /**
     * handling the delete logic
     * param:
     * $errors for string errors and send them to the frontend
     * $deleteSucsses for checking if the delete is compleat saftly
     */
    public function delete($commentId , $photoId){
        $errors = [];
        //check authorization.
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        //store the result of delete query 
        $deleteSucsses = $this->commentModel->deleteComment($commentId , Session::getCurrentUserId());
        // check the success of deletion if it fails it will redirect to the front and return errors.
        if(!$deleteSucsses){
            $_SESSION['errors'] = ['comment' => ['error deleting comment.']];
            header('Location: /photo/'.$photoId);
            unset($_SESSION['errors']);
            exit;
        }
        //redirct to photo detail page.
        header('Location: /photo/'.$photoId);
        exit;
    }
}