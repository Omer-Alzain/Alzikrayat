<?php
//imort the nedded files.
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/PhotoModel.php';
//has onley one job to get the photos from the database and send it to the heme page
class HomeController extends Controller
{
    /**
     * function return the photos to the home page
     * params:
     * $photoModel an of photoModel class.
     * $allPhotos store all photos metadata.
     * $previewPhotos store 3 photos from the whole set.
     */
    public function index()
    {
        //object of photoModel class
        $photoModel = new PhotoModel();
        //store all the photos 
        $allPhotos = $photoModel->getAllPhotos();
        //this a preview of the whole gallery so send just 3 .
        $previewPhotos = array_slice($allPhotos, 0, 3);
        //redirect to the home page and send the photos
        $this->view('home', ['previewPhotos' => $previewPhotos]);
    }
}