<?php

declare(strict_types=1);

class HomeController extends Controller
{
    public function index(): void
    {
        $eventModel = new Event();
        $categoryModel = new Category();

        $this->render('home', [
            'pageTitle' => 'Domov',
            'events' => $eventModel->upcoming(3),
            'categories' => $categoryModel->all(),
        ]);
    }
}
