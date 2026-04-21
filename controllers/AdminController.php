<?php

declare(strict_types=1);

class AdminController extends Controller
{
    public function dashboard(): void
    {
        $this->requireAdmin();

        $eventModel = new Event();
        $categoryModel = new Category();
        $messageModel = new ContactMessage();

        $this->render('admin/dashboard', [
            'pageTitle' => 'Administrácia',
            'eventsCount' => $eventModel->count(),
            'upcomingCount' => $eventModel->countUpcoming(),
            'categoriesCount' => $categoryModel->count(),
            'messagesCount' => $messageModel->count(),
            'latestEvents' => $eventModel->latest(5),
            'latestMessages' => $messageModel->latest(5),
        ], true);
    }
}
