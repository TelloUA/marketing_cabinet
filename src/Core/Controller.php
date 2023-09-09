<?php

namespace App\Core;

class Controller
{
    public Model $model;
    public View $view;

    function __construct()
    {
        $this->view = new View();
    }

    function action_index()
    {
    }
}