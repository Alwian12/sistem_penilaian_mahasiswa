<?php
$isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ProgramStudiModel.php';
require_once __DIR__ . '/../models/MahasiswaModel.php';
require_once __DIR__ . '/../models/DosenModel.php';
require_once __DIR__ . '/../models/MataKuliahModel.php';
require_once __DIR__ . '/../models/TahunAkademikModel.php';
require_once __DIR__ . '/../models/KrsModel.php';
require_once __DIR__ . '/../models/NilaiModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/DosenController.php';
require_once __DIR__ . '/../controllers/MahasiswaController.php';
require_once __DIR__ . '/../controllers/MasterDataController.php';
require_once __DIR__ . '/../controllers/KrsController.php';
require_once __DIR__ . '/../controllers/NilaiController.php';
require_once __DIR__ . '/../controllers/PerhitunganController.php';

send_security_headers();

$dbConfig = require __DIR__ . '/../config/database.php';

try {
    $db = new Database($dbConfig);
    $pdo = $db->getConnection();
} catch (Throwable $e) {
    http_response_code(503);
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Database Tidak Tersedia</title><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-slate-100 min-h-screen flex items-center justify-center"><div class="max-w-lg rounded-xl bg-white p-8 shadow-lg text-center"><div class="mb-4 text-5xl">⚠️</div><h1 class="text-2xl font-bold text-slate-800 mb-2">Database belum aktif</h1><p class="text-slate-600 mb-4">Server MySQL Laragon belum berjalan atau koneksi belum dikonfigurasi.</p><pre class="overflow-auto rounded bg-slate-100 p-3 text-left text-xs text-slate-700">' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre><p class="mt-4 text-sm text-slate-500">Lanjutkan dengan menyalakan MySQL di Laragon dan menjalankan script SQL dari folder database.</p></div></body></html>';
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$basePath = $scriptDir === '/' ? '' : $scriptDir;

if ($basePath !== '' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

if ($uri === '') {
    $uri = '/';
}

if ($uri !== '/' && strlen($uri) > 1 && str_ends_with($uri, '/')) {
    $uri = rtrim($uri, '/');
}

$routeMap = [
    '/' => ['AuthController', 'welcome'],
    '/login' => ['AuthController', 'login'],
    '/register' => ['AuthController', 'register'],
    '/register/dosen' => ['AuthController', 'registerDosen'],
    '/logout' => ['AuthController', 'logout'],
    '/admin/dashboard' => ['AdminController', 'dashboard'],
    '/admin/dashboard/live' => ['AdminController', 'liveData'],
    '/admin/program-studi' => ['MasterDataController', 'programStudi'],
    '/admin/mahasiswa' => ['MasterDataController', 'mahasiswa'],
    '/admin/dosen' => ['MasterDataController', 'dosen'],
    '/admin/mata-kuliah' => ['MasterDataController', 'mataKuliah'],
    '/admin/tahun-akademik' => ['MasterDataController', 'tahunAkademik'],
    '/admin/krs' => ['KrsController', 'index'],
    '/admin/krs/create' => ['KrsController', 'create'],
    '/admin/krs/delete' => ['KrsController', 'delete'],
    '/admin/nilai' => ['NilaiController', 'index'],
    '/admin/ips' => ['PerhitunganController', 'ips'],
    '/admin/ipk' => ['PerhitunganController', 'ipk'],
    '/admin/transkrip' => ['PerhitunganController', 'transkrip'],
    '/dosen/dashboard' => ['DosenController', 'dashboard'],
    '/dosen/dashboard/live' => ['DosenController', 'liveData'],
    '/mahasiswa/dashboard' => ['MahasiswaController', 'dashboard'],
    '/mahasiswa/dashboard/live' => ['MahasiswaController', 'liveData'],
    '/mahasiswa/krs' => ['KrsController', 'mandiri'],
];

$action = $routeMap[$uri] ?? null;

if (!$action) {
    http_response_code(404);
    echo 'Halaman tidak ditemukan';
    exit;
}

[$controllerName, $method] = $action;

if (!class_exists($controllerName)) {
    require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
}

if (in_array($controllerName, ['AdminController', 'MasterDataController', 'KrsController', 'NilaiController', 'PerhitunganController', 'DosenController', 'MahasiswaController'], true)) {
    $controller = new $controllerName($pdo);
} elseif ($controllerName === 'AuthController') {
    $controller = new $controllerName(new UserModel($pdo), $pdo);
} else {
    $controller = new $controllerName();
}

$controller->$method();
