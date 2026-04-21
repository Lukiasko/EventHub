<?php

declare(strict_types=1);

class CategoryController extends Controller
{
    private Category $categoryModel;
    private Event $eventModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->eventModel = new Event();
    }

    public function adminIndex(): void
    {
        $this->requireAdmin();

        $this->render('admin/categories/index', [
            'pageTitle' => 'Správa kategórií',
            'categories' => $this->categoryModel->all(),
        ], true);
    }

    public function adminCreate(): void
    {
        $this->requireAdmin();
        $errors = [];
        $category = ['name' => ''];

        if (is_post()) {
            $category = $_POST;
            $errors = $this->validateCategory($category);

            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            if ($errors === []) {
                $this->categoryModel->create($category);
                Session::flash('success', 'Kategória bola vytvorená.');
                redirect('admin_categories');
            }
        }

        $this->render('admin/categories/create', [
            'pageTitle' => 'Nová kategória',
            'errors' => $errors,
            'category' => $category,
        ], true);
    }

    public function adminEdit(): void
    {
        $this->requireAdmin();
        $id = request_int('id');
        $category = $id ? $this->categoryModel->find($id) : null;

        if (!$category) {
            Session::flash('error', 'Kategória nebola nájdená.');
            redirect('admin_categories');
        }

        $errors = [];

        if (is_post()) {
            $category = array_merge($category, $_POST);
            $errors = $this->validateCategory($category);

            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            if ($errors === []) {
                $this->categoryModel->update((int) $category['id'], $category);
                Session::flash('success', 'Kategória bola upravená.');
                redirect('admin_categories');
            }
        }

        $this->render('admin/categories/edit', [
            'pageTitle' => 'Upraviť kategóriu',
            'errors' => $errors,
            'category' => $category,
        ], true);
    }

    public function adminDelete(): void
    {
        $this->requireAdmin();
        $id = request_int('id');
        $category = $id ? $this->categoryModel->find($id) : null;

        if (!$category) {
            Session::flash('error', 'Kategória nebola nájdená.');
            redirect('admin_categories');
        }

        $eventsCount = $this->eventModel->countByCategory((int) $category['id']);

        if (is_post()) {
            if (!validate_csrf()) {
                Session::flash('error', 'Formulár nie je platný. Skúste akciu zopakovať.');
                redirect('admin_categories');
            }

            if ($eventsCount > 0) {
                Session::flash('error', 'Kategóriu nemožno odstrániť, pretože obsahuje podujatia.');
                redirect('admin_categories');
            }

            $this->categoryModel->delete((int) $category['id']);
            Session::flash('success', 'Kategória bola odstránená.');
            redirect('admin_categories');
        }

        $this->render('admin/categories/delete', [
            'pageTitle' => 'Odstrániť kategóriu',
            'category' => $category,
            'eventsCount' => $eventsCount,
        ], true);
    }

    private function validateCategory(array $data): array
    {
        $errors = [];

        if (trim((string) ($data['name'] ?? '')) === '') {
            $errors[] = 'Názov kategórie je povinný.';
        }

        return $errors;
    }
}
