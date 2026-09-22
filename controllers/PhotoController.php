<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/PhotoModel.php';
require_once __DIR__ . '/../core/Validator.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../controllers/CommentController.php';


class PhotoController extends Controller{
    private $photoModel;

    public function __construct()
    {
        $this->photoModel = new PhotoModel();
    }

    public function index()
    {
        
        $photos = $this->photoModel->getAllPhotos();
        $data = [
            'photos' => $photos
        ];
        $this->view('photos/gallery', $data);
    }
    public function show($id)
    {
        $photo = $this->photoModel->getPhotoById($id);
        $comments = CommentController ::getCommentsOnPhoto($id);
        if ($photo) {
            $data = [
                'photo' => $photo
            ];
            if($comments){
                $data = [
                    'photo' => $photo,
                    'comments' => $comments
                ];
            }
            $this->view('photos/photoDetails', $data);
        } else {
            // Handle photo not found (e.g., redirect to gallery or show an error message)
            header('Location: /gallery');
            exit;
        }
    }
    public function create()
    {
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        $this->view('photos/upload');
    }
    public function store()
    {
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Session::getCurrentUserId(); // Assuming user ID is stored in session
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
            ];
            $file = $_FILES['photo'] ?? null;

            //Validate the input data
            $rules = [
                'title' => ['required'],
                'description' => ['optional'],
            ];
            $errors = Validator::validate($data, $rules);
            // Validate the uploaded file
            $imageErrors = Validator::validateImage($file);
            if (!empty($errors) || !empty($imageErrors)) {
                // Handle validation errors (e.g., redirect back with error messages)
                $_SESSION['errors'] = $errors;
                header('Location: /photos/upload');
                exit;
            }

            // Move the uploaded file to a desired location
            $uploadDir = __DIR__ . '/../public/images/uploads/';
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('photo_', true) . '.' . $extension;
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                // Save photo details to the database
                $photoId = $this->photoModel->createPhoto([
                    'user_id' => Session::getCurrentUserId(),
                    'file_name' => $fileName,
                    'title' => $data['title'],
                    'description' => $data['description']
                ]);
                header('Location: /photo/' . $photoId);
                exit;
            } else {
                // Handle file upload error
                $_SESSION['errors'] = ['photo' => ['Failed to upload the photo.']];
                header('Location: /photos/create');
                exit;
            }
        }
    }
    public function delete($id)
    {
        if(!Session::isLoggedIn()){
            header('Location: /auth/login');
            exit;
        }
        $photo = $this->photoModel->getPhotoById($id);
        $userId = Session::getCurrentUserId();
        if (!$photo || (int) $photo['user_id'] !== $userId) {
            // Handle the case where the photo does not belong to the user or does not exist
            $_SESSION['errors'] = ['photo' => ['You do not have permission to delete this photo.']];
            header('Location: /gallery');
            exit;
        }
        $deleteSuccess = $this->photoModel->deletePhoto($id, $userId);
        if (!$deleteSuccess) {
            $_SESSION['errors'] = ['photo' => ['Failed to delete the photo.']];
            header('Location: /gallery');
            exit;
        }
        $filePath = __DIR__ . '/../public/images/uploads/' . $photo['file_name'];
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file from the server
        }else {
            $_SESSION['errors'] = ['photo' => ['Failed to delete the photo file from the server.']];
        }
        header('Location: /gallery');
        exit;
    }
}