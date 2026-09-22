<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/PhotoModel.php';
class HomeController extends Controller
{
    public function index()
    {
        $photoModel = new PhotoModel();
        $allPhotos = $photoModel->getAllPhotos();
        $previewPhotos = array_slice($allPhotos, 0, 3);

        $this->view('home', ['previewPhotos' => $previewPhotos]);
    }
}