<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/PhotoModel.php';
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
        if ($photo) {
            $data = [
                'photo' => $photo
            ];
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
                'photo' => ['required', 'image']
            ];
            Validator::validate($data, $rules);
            // Validate the uploaded file
            $errors = Validator::validateImage($file);
            if (!empty($errors)) {
                // Handle validation errors (e.g., redirect back with error messages)
                $_SESSION['errors'] = $errors;
                header('Location: /photos/create');
                exit;
            }

            // Move the uploaded file to a desired location
            $uploadDir = __DIR__ . '/../images/uploads/';
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
                header('Location: /photos/' . $photoId);
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
        this->photoModel->deletePhoto($id, Session::getCurrentUserId());
        header('Location: /photos/gallery');
        exit;
    }
}