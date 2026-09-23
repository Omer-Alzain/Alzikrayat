<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/PhotoModel.php';
require_once __DIR__ . '/../core/Validator.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/CommentModel.php';


class PhotoController extends Controller{
    
    private $photoModel;
    private $commentModel;

    public function __construct()
    {
        $this->photoModel = new PhotoModel();
        $this->commentModel = new CommentModel();
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
        if ($photo) {
            $comments = $this->commentModel->getCommentsByPhotoId($id);
            $data = [
                'photo' => $photo,
                'comments' => $comments
            ];
            $this->view('photos/photoDetails', $data);
        } else {
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
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        $this->view('photos/upload', ['errors' => $errors]);
    }
    public function store()
{
    if(!Session::isLoggedIn()){
        header('Location: /auth/login');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log('=== STORE HIT ===');

        $userId = Session::getCurrentUserId();
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];
        $file = $_FILES['photo'] ?? null;

        $rules = [
            'title' => ['required'],
            'description' => ['optional'],
        ];
        $errors = Validator::validate($data, $rules);
        $imageErrors = Validator::validateImage($file);

        error_log('title errors: ' . print_r($errors, true));
        error_log('image errors: ' . print_r($imageErrors, true));

        if (!empty($imageErrors)) {
            $errors['photo'] = $imageErrors;
        }

        if (!empty($errors)) {
            error_log('BAILING OUT — validation failed');
            $_SESSION['errors'] = $errors;
            header('Location: /upload');
            exit;
        }

        error_log('VALIDATION PASSED — attempting file move');

        $uploadDir = __DIR__ . '/../public/images/uploads/';
        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('photo_', true) . '.' . $extension;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
            error_log('FILE MOVED OK — inserting into DB');
            $photoId = $this->photoModel->createPhoto([
                'user_id' => Session::getCurrentUserId(),
                'file_name' => $fileName,
                'title' => $data['title'],
                'description' => $data['description']
            ]);
            error_log('DB INSERT RETURNED ID: ' . $photoId);
            header('Location: /photo/' . $photoId);
            exit;
        } else {
            error_log('FILE MOVE FAILED');
            $_SESSION['errors'] = ['photo' => ['Failed to upload the photo.']];
            header('Location: /upload');
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
            unset($_SESSION['errors']);
            exit;
        }
        $deleteSuccess = $this->photoModel->deletePhoto($id, $userId);
        if (!$deleteSuccess) {
            $_SESSION['errors'] = ['photo' => ['Failed to delete the photo.']];
            header('Location: /gallery');
            unset($_SESSION['errors']);
            exit;
        }
        $filePath = __DIR__ . '/../public/images/uploads/' . $photo['file_name'];
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file from the server
        }
        header('Location: /gallery');
        exit;
    }
}