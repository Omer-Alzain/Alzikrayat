<?php
//class controller control the flow of data and redirection to the front
class Controller
{
    /**
     * Load a view file.
     */
    protected function view($view, $data = [])
    {
        //extract data into variables can be used directly in the front
        extract($data);

        //redirection to the page.
        require __DIR__ . '/../views/' . $view . '.php';
    }
}