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
}