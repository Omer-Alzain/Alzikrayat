<?php
//import the neede files.
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/PhotoModel.php';
require_once __DIR__ . '/../core/Validator.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/CommentModel.php';

/**
 * a class that control trafic and handel the logic of the photos.
 * functions:__construct,index,show,create,store and delete.
 * Params:$photoModel,$commentModel for storing object of the nedded model.
 */
class PhotoController extends Controller{
    
    private $photoModel;
    private $commentModel;

    // initialize the objects.
    public function __construct()
    {
        $this->photoModel = new PhotoModel();
        $this->commentModel = new CommentModel();
    }

    /**return all the photos to the gallery page.
     * params:$photos to store metedata from the database.
     * $data to store the photos in a prober way to send it to the front end.
     * redirect to gallery page on get requst and send the photos.
    */
    public function index()
    {
        //retrive the photos.
        $photos = $this->photoModel->getAllPhotos();
        $data = [
            'photos' => $photos
        ];
        //redirect to the gallery page.
        $this->view('photos/gallery', $data);
    }
    /**
     * function that show the detail photo page with the photo and its metadata.
     * Params:
     * $photo store the photo.
     * /$comments store comments on that photo.
     * $data store the data that will be sent to the front end.
     * redirect to the photo detail page.
     */
    public function show($id)
    {
        //retrive photo from its taple.
        $photo = $this->photoModel->getPhotoById($id);
        //check if the photo exist procede defult if not redirect to the gallery page.
        if ($photo) {
            //retrive comments on the subject photo
            $comments = $this->commentModel->getCommentsByPhotoId($id);
            $data = [
                'photo' => $photo,
                'comments' => $comments
            ];
            //redirect to the photo details page with the info.
            $this->view('photos/photoDetails', $data);
        } else {
            //redirect to gallery.
            header('Location: /gallery');
            exit;
        }
    }
    /**
     * methos handling get request redirect user to the upload page-
     * -if logged in and to the login page if not.
     * Params:$errors store the errors to show them to the user.
     * redirect to upload with errors if any was there or login.
     */
    public function create()
    {
        //check if user is not logged in
        if(!Session::isLoggedIn()){
            //redirect to login page
            header('Location: /auth/login');
            exit;
        }
        //store errors capctured by current session
        $errors = $_SESSION['errors'] ?? [];
        //empty errors from the session.
        unset($_SESSION['errors']);
        //redirect to upload.
        $this->view('photos/upload', ['errors' => $errors]);
    }

    /**
     * handling validate and storing of the photo.
     * Params:
     * $userId store the user id.
     * $data store the metadata of the photo
     * $file store the photo.
     * $rules store rules to be passed to validator.
     * $errors store errors on the proccess.
     * $imageErrors store image errors.
     * $uploadDir store the directory the image will be saved to 
     * $extension store the extention of file eg: .jpg etc.
     * $fileName store a uniq name for the photo file.
     * $filePath store the path the photo will be stored in.
     * $photoId store the new photo id used to redirect to the page.
     * redirect user to login page if no logged in and procede with normal 
     * opration if he is then redirect to the photo detail page if succeed and to 
     * upload if faild.
     */
    public function store()
    {
        //check if user not logged in and direct to the login page if the the case.
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        //check if the request is post and procede defult flow if it is.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // store the user id in the currunt session 
            $userId = Session::getCurrentUserId();
            //store metadata of the photo
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
            ];
            //store the photo file
            $file = $_FILES['photo'] ?? null;
            //store the rules to validate the data
            $rules = [
                'title' => ['required'],
                'description' => ['optional'],
            ];
            //store the result of validating metadata. 
            $errors = Validator::validate($data, $rules);
            //store the result of validating the image file.
            $imageErrors = Validator::validateImage($file);

            //check if there is errors in image and store them if there was
            if (!empty($imageErrors)) {
                $errors['photo'] = $imageErrors;
            }

            //check if there is errors then redirect to upload page.
            if (!empty($errors)) {
                //store the errors in the session errors.
                $_SESSION['errors'] = $errors;
                header('Location: /upload');
                exit;
            }

            //handling the image file.
            $uploadDir = __DIR__ . '/../public/images/uploads/';
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('photo_', true) . '.' . $extension;
            $filePath = $uploadDir . $fileName;

            //check if the image file moved saftly.
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                // store the photo in the database and keep photo id.
                $photoId = $this->photoModel->createPhoto([
                    'user_id' => Session::getCurrentUserId(),
                    'file_name' => $fileName,
                    'title' => $data['title'],
                    'description' => $data['description']
                ]);
                //redirect to the new photo deatil page.
                header('Location: /photo/' . $photoId);
                exit;
            } else {
                //store the error of failed file moving and redirect to the upload.
                $_SESSION['errors'] = ['photo' => ['Failed to upload the photo.']];
                header('Location: /upload');
                exit;
            }
        }
    }
    /**
     * handling the deletion of a photo
     * Params:
     * $photo store the metadata of the photo.
     * $userId store the id of the user.
     * $deleteSuccess store a bool that refer to the success of deletin photo metadata.
     * $filePath store the file path to the photo which is subject to deletion.
     * redirect to the gallery or to login.
     */
    public function delete($id)
    {
        //checks if the user is not logged in to direct them to login page.
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        //store the photo data.
        $photo = $this->photoModel->getPhotoById($id);
        //store user id 
        $userId = Session::getCurrentUserId();
        //check if there is not a photo with that id and if the owner of the photo is not this user.
        if (!$photo || (int) $photo['user_id'] !== $userId) {
            // Handle the case where the photo does not belong to the user or does not exist
            $_SESSION['errors'] = ['photo' => ['You do not have permission to delete this photo.']];
            header('Location: /gallery');
            unset($_SESSION['errors']);
            exit;
        }
        // store the result of trying to delete the photo.
        $deleteSuccess = $this->photoModel->deletePhoto($id, $userId);
        //check if the delete failed.
        if (!$deleteSuccess) {
            //handel the failure delete.
            $_SESSION['errors'] = ['photo' => ['Failed to delete the photo.']];
            header('Location: /gallery');
            unset($_SESSION['errors']);
            exit;
        }
        //store the file path
        $filePath = __DIR__ . '/../public/images/uploads/' . $photo['file_name'];
        //check if there is image file with this path
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file from the server
        }
        header('Location: /gallery');
        exit;
    }
}