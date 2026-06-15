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

        $userModel = new User();
        $userId = Session::get('user_id') !== null ? (int) Session::get('user_id') : null;

        $this->render('event_detail', [
            'pageTitle' => $event['title'],
            'event' => $event,
            'isLoggedIn' => $userId !== null,
            'isRegistered' => $userId !== null
                ? $userModel->isRegisteredForEvent($userId, (int) $event['id'])
                : false,
        ]);
    }

    public function downloadIcs(): void
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

        $startTs = strtotime((string) $event['event_date']);
        if ($startTs === false) {
            $startTs = time();
        }
        $endTs = $startTs + 2 * 3600; // predvolená dĺžka 2 hodiny

        $uid = 'event-' . $event['id'] . '@eventhub';
        $dtstamp = gmdate('Ymd\THis\Z');
        $dtstart = gmdate('Ymd\THis\Z', $startTs);
        $dtend = gmdate('Ymd\THis\Z', $endTs);

        $summary = preg_replace('/[\r\n]+/', ' ', $event['title'] ?? 'Podujatie');
        $location = preg_replace('/[\r\n]+/', ' ', $event['location'] ?? '');
        $description = preg_replace('/[\r\n]+/', '\\n', $event['description'] ?? '');

        $ics = [];
        $ics[] = 'BEGIN:VCALENDAR';
        $ics[] = 'VERSION:2.0';
        $ics[] = 'PRODID:-//EventHub//EN';
        $ics[] = 'BEGIN:VEVENT';
        $ics[] = 'UID:' . $uid;
        $ics[] = 'DTSTAMP:' . $dtstamp;
        $ics[] = 'DTSTART:' . $dtstart;
        $ics[] = 'DTEND:' . $dtend;
        $ics[] = 'SUMMARY:' . $this->foldIcsLine($summary);
        if ($location !== '') {
            $ics[] = 'LOCATION:' . $this->foldIcsLine($location);
        }
        if ($description !== '') {
            $ics[] = 'DESCRIPTION:' . $this->foldIcsLine($description);
        }
        $ics[] = 'END:VEVENT';
        $ics[] = 'END:VCALENDAR';

        $content = implode("\r\n", $ics) . "\r\n";

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="event-' . $event['id'] . '.ics"');
        echo $content;
        exit;
    }

    private function foldIcsLine(string $text): string
    {
        $text = trim($text);
        // replace CRLF with escaped newline
        $text = str_replace(["\r\n", "\r", "\n"], '\\n', $text);
        // minimal folding: ensure no very long lines (75 octets recommended)
        $max = 70;
        $result = '';
        $len = strlen($text);
        for ($i = 0; $i < $len; $i += $max) {
            $part = substr($text, $i, $max);
            if ($i === 0) {
                $result .= $part;
            } else {
                $result .= '\\n' . $part;
            }
        }
        return $result;
    }

    public function registerForEvent(): void
    {
        $this->requireUser();

        $eventId = request_int('id');
        $event = $eventId ? $this->eventModel->find($eventId) : null;
        $userId = (int) Session::get('user_id');

        if (!$event) {
            Session::flash('error', 'Podujatie nebolo nájdené.');
            redirect('events');
        }

        if (!is_post()) {
            redirect('event_detail', ['id' => $eventId]);
        }

        if (!validate_csrf()) {
            Session::flash('error', 'Formulár nie je platný. Skúste to znova.');
            redirect('event_detail', ['id' => $eventId]);
        }

        $userModel = new User();

        if ($userModel->isRegisteredForEvent($userId, (int) $event['id'])) {
            Session::flash('error', 'Na tomto podujatí už ste prihlásený.');
            redirect('event_detail', ['id' => $eventId]);
        }

        $userModel->registerForEvent($userId, (int) $event['id']);
        Session::flash('success', 'Na podujatie ste sa úspešne prihlásili.');
        redirect('event_detail', ['id' => $eventId]);
    }

    public function unregisterFromEvent(): void
    {
        $this->requireUser();

        $eventId = request_int('id');
        $event = $eventId ? $this->eventModel->find($eventId) : null;
        $userId = (int) Session::get('user_id');

        if (!$event) {
            Session::flash('error', 'Podujatie nebolo nájdené.');
            redirect('events');
        }

        if (!is_post()) {
            redirect('event_detail', ['id' => $eventId]);
        }

        if (!validate_csrf()) {
            Session::flash('error', 'Formulár nie je platný. Skúste to znova.');
            redirect('event_detail', ['id' => $eventId]);
        }

        $userModel = new User();
        $userModel->unregisterFromEvent($userId, (int) $event['id']);
        Session::flash('success', 'Odhlásenie z podujatia bolo úspešné.');
        redirect('event_detail', ['id' => $eventId]);
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

        $eventDate = trim((string) ($data['event_date'] ?? ''));
        if ($eventDate === '') {
            $errors[] = 'Dátum podujatia je povinný.';
        } elseif (strtotime($eventDate) === false) {
            $errors[] = 'Zadajte platný dátum podujatia.';
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
