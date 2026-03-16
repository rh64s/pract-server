<?php
namespace Controller;

class Site
{
    public function index(): void
    {
        echo 'Hello World! This is the home page.';
    }

    public function hello(): void
    {
        echo 'Hello World! This is "Hello" file!';
    }
}