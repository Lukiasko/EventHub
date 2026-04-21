<?php

declare(strict_types=1);

class EventController extends Controller
{
    private Event $eventModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->eventModel = new Event();
        $this->categoryModel = new Category();
    }

    public function index(): void
    {
        $categoryId = request_int('category');

        $this->render('events', [
            'pageTitle' => 'Podujatia',
            'events' => $this->eventModel->all($categoryId),
            'categories' => $this->categoryModel->all(),
            'selectedCategory' => $categoryId,
        ]);
    }

    public function detail(): void
    {
        $id = request_int('id');
        $event = $id ? $this->eventModel->find($id) : null;

        if (!$event) {
            http_response_code(404);
            $this->render('error', [
                'pageTitle' => 'Podujatie nenájdené',
                'errorMessage' => 'Hľadané podujatie neexistuje alebo bolo odstránené.',
            ]);
            return;
        }

        $this->render('event_detail', [
            'pageTitle' => $event['title'],
            'event' => $event,
        ]);
    }

    public function adminIndex(): void
    {
        $this->requireAdmin();

        $this->render('admin/events/index', [
            'pageTitle' => 'Správa podujatí',
            'events' => $this->eventModel->all(),
        ], true);
    }

    public function adminCreate(): void
    {
        $this->requireAdmin();
        $errors = [];
        $data = $this->emptyEventData();

        if (is_post()) {
            $data = $_POST;
            $errors = $this->validateEvent($data);

            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            if ($errors === []) {
                $this->eventModel->create($data);
                Session::flash('success', 'Podujatie bolo úspešne vytvorené.');
                redirect('admin_events');
            }
        }

        $this->render('admin/events/create', [
            'pageTitle' => 'Nové podujatie',
            'categories' => $this->categoryModel->all(),
            'errors' => $errors,
            'event' => $data,
        ], true);
    }

    public function adminEdit(): void
    {
        $this->requireAdmin();
        $id = request_int('id');
        $event = $id ? $this->eventModel->find($id) : null;

        if (!$event) {
            Session::flash('error', 'Podujatie nebolo nájdené.');
            redirect('admin_events');
        }

        $errors = [];
        $data = $event;

        if (is_post()) {
            $data = array_merge($event, $_POST);
            $errors = $this->validateEvent($data);

            if (!validate_csrf()) {
                $errors[] = 'Formulár nie je platný. Skúste ho odoslať znova.';
            }

            if ($errors === []) {
                $this->eventModel->update((int) $event['id'], $data);
                Session::flash('success', 'Podujatie bolo úspešne upravené.');
                redirect('admin_events');
            }
        }

        $this->render('admin/events/edit', [
            'pageTitle' => 'Upraviť podujatie',
            'categories' => $this->categoryModel->all(),
            'errors' => $errors,
            'event' => $data,
        ], true);
    }

    public function adminDelete(): void
    {
        $this->requireAdmin();
        $id = request_int('id');
        $event = $id ? $this->eventModel->find($id) : null;

        if (!$event) {
            Session::flash('error', 'Podujatie nebolo nájdené.');
            redirect('admin_events');
        }

        if (is_post()) {
            if (!validate_csrf()) {
                Session::flash('error', 'Formulár nie je platný. Skúste akciu zopakovať.');
                redirect('admin_events');
            }

            $this->eventModel->delete((int) $event['id']);
            Session::flash('success', 'Podujatie bolo odstránené.');
            redirect('admin_events');
        }

        $this->render('admin/events/delete', [
            'pageTitle' => 'Odstrániť podujatie',
            'event' => $event,
        ], true);
    }

    private function validateEvent(array $data): array
    {
        $errors = [];

        if (trim((string) ($data['title'] ?? '')) === '') {
            $errors[] = 'Názov podujatia je povinný.';
        }

        if (trim((string) ($data['description'] ?? '')) === '') {
            $errors[] = 'Popis podujatia je povinný.';
        }

        if (trim((string) ($data['location'] ?? '')) === '') {
            $errors[] = 'Miesto konania je povinné.';
        }

        if (trim((string) ($data['event_date'] ?? '')) === '') {
            $errors[] = 'Dátum podujatia je povinný.';
        }

        $categoryId = filter_var($data['category_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$categoryId || !$this->categoryModel->find((int) $categoryId)) {
            $errors[] = 'Vyberte platnú kategóriu.';
        }

        return $errors;
    }

    private function emptyEventData(): array
    {
        return [
            'category_id' => '',
            'title' => '',
            'description' => '',
            'location' => '',
            'event_date' => '',
            'image' => '',
        ];
    }
}
