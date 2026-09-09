<?php

class Controller
{
    /**
     * Load a view file.
     */
    protected function view($view, $data = [])
    {
        extract($data);

        require __DIR__ . '/../views/' . $view . '.php';
    }
}