<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Debug extends CI_Controller
{
    public function index()
    {
        echo "<h1>Debug Info</h1>";
        echo "<pre>";
        echo "Base URL: " . base_url() . "<br>";
        echo "Current URI: " . uri_string() . "<br>";
        echo "Routes: <br>";
        print_r($this->router->routes);
        echo "</pre>";
    }
}