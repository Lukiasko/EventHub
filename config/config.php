<?php

declare(strict_types=1);

const APP_NAME = 'EventHub';
const APP_ROOT = __DIR__ . '/..';
const BASE_URL = 'index.php';

const DB_HOST = 'localhost';
const DB_NAME = 'eventhub';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

const UPLOAD_URL = 'public/uploads/';
const DEFAULT_EVENT_IMAGE = 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=1200&q=80';

date_default_timezone_set('Europe/Bratislava');

require_once APP_ROOT . '/core/Helpers.php';
