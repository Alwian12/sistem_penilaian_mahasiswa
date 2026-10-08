<?php

class AuthController
{
    private UserModel $userModel;
    private PDO $pdo;

    public function __construct(UserModel $userModel, PDO $pdo)
    {
        $this->userModel = $userModel;
        $this->pdo = $pdo;
    }

    public function login(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirectByRole((string)$_SESSION['user']['role']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $lockedUntil = (int)($_SESSION['login_locked_until'] ?? 0);
            if ($lockedUntil > time()) {
                flash('error', 'Terlalu banyak percobaan. Coba lagi dalam ' . ($lockedUntil - time()) . ' detik.');
                view('auth/login', ['pageTitle' => 'Login']);
                return;
            }

            $identifier = trim($_POST['identifier'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($identifier === '' || $password === '') {
                flash('error', 'Username/email dan password wajib diisi.');
                view('auth/login', ['pageTitle' => 'Login']);
                return;
            }

            $user = $this->userModel->findByUsernameOrEmail($identifier);

            if (!$user || !password_verify($password, $user['password'])) {
                $attempts = (int)($_SESSION['login_attempts'] ?? 0) + 1;
                $_SESSION['login_attempts'] = $attempts;
                if ($attempts >= 5) {
                    $_SESSION['login_locked_until'] = time() + 60;
                    $_SESSION['login_attempts'] = 0;
                }
                flash('error', 'Username/email atau password salah.');
                view('auth/login', ['pageTitle' => 'Login']);
                return;
            }

            if ((int) $user['status'] !== 1) {
                flash('error', 'Akun Anda sedang tidak aktif.');
                view('auth/login', ['pageTitle' => 'Login']);
                return;
            }

            session_regenerate_id(true);
            unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role_name'] ?? 'admin',
                'nama' => $user['nama'] ?? $user['username'],
            ];

            $this->redirectByRole($_SESSION['user']['role']);
            return;
        }

        view('auth/login', ['pageTitle' => 'Login']);
    }

    public function welcome(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirectByRole((string)$_SESSION['user']['role']);
            return;
        }

        $programStudi = (new ProgramStudiModel($this->pdo))->getAll();
        view('auth/welcome', ['pageTitle' => 'STMIK Triguna Dharma', 'programStudi' => $programStudi]);
    }

    public function register(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirectByRole((string)$_SESSION['user']['role']);
            return;
        }

        $programStudi = (new ProgramStudiModel($this->pdo))->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $data = [
                'nama' => trim($_POST['nama'] ?? ''),
                'nim' => trim($_POST['nim'] ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'no_hp' => trim($_POST['no_hp'] ?? ''),
                'program_studi_id' => (int)($_POST['program_studi_id'] ?? 0),
                'angkatan' => (int)($_POST['angkatan'] ?? 0),
                'password' => (string)($_POST['password'] ?? ''),
                'password_confirmation' => (string)($_POST['password_confirmation'] ?? ''),
            ];

            $validationError = $this->validateRegistration($data);
            if ($validationError !== null) {
                flash('error', $validationError);
                view('auth/register', ['pageTitle' => 'Pendaftaran Mahasiswa', 'programStudi' => $programStudi, 'form' => $data]);
                return;
            }

            try {
                $this->pdo->beginTransaction();
                $roleId = (int)$this->pdo->query("SELECT id FROM roles WHERE nama_role = 'mahasiswa' LIMIT 1")->fetchColumn();
                if ($roleId <= 0) {
                    throw new RuntimeException('Role mahasiswa belum tersedia.');
                }

                $userStmt = $this->pdo->prepare('INSERT INTO users (username, email, password, nama, role_id, status) VALUES (:username, :email, :password, :nama, :role_id, 1)');
                $userStmt->execute([
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                    'nama' => $data['nama'],
                    'role_id' => $roleId,
                ]);

                $mahasiswaStmt = $this->pdo->prepare('INSERT INTO mahasiswa (nim, nama, email, no_hp, program_studi_id, angkatan, status, user_id) VALUES (:nim, :nama, :email, :no_hp, :program_studi_id, :angkatan, 1, :user_id)');
                $mahasiswaStmt->execute([
                    'nim' => $data['nim'],
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'no_hp' => $data['no_hp'],
                    'program_studi_id' => $data['program_studi_id'],
                    'angkatan' => $data['angkatan'],
                    'user_id' => (int)$this->pdo->lastInsertId(),
                ]);

                $this->pdo->commit();
                flash('success', 'Pendaftaran berhasil. Silakan masuk menggunakan akun baru Anda.');
                redirect('/login');
            } catch (Throwable $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                flash('error', 'Pendaftaran gagal. Username, email, atau NIM mungkin sudah digunakan.');
                view('auth/register', ['pageTitle' => 'Pendaftaran Mahasiswa', 'programStudi' => $programStudi, 'form' => $data]);
            }
            return;
        }

        view('auth/register', ['pageTitle' => 'Pendaftaran Mahasiswa', 'programStudi' => $programStudi, 'form' => []]);
    }

    public function registerDosen(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirectByRole((string)$_SESSION['user']['role']);
            return;
        }

        $programStudi = (new ProgramStudiModel($this->pdo))->getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $data = [
                'nama' => trim($_POST['nama'] ?? ''),
                'nidn' => trim($_POST['nidn'] ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'no_hp' => trim($_POST['no_hp'] ?? ''),
                'program_studi_id' => (int)($_POST['program_studi_id'] ?? 0),
                'password' => (string)($_POST['password'] ?? ''),
                'password_confirmation' => (string)($_POST['password_confirmation'] ?? ''),
            ];

            $error = $this->validateStaffRegistration($data, 'NIDN');
            if ($error !== null) {
                flash('error', $error);
                view('auth/register-dosen', ['pageTitle' => 'Pendaftaran Dosen', 'programStudi' => $programStudi, 'form' => $data]);
                return;
            }

            try {
                $this->pdo->beginTransaction();
                $roleId = (int)$this->pdo->query("SELECT id FROM roles WHERE nama_role = 'dosen' LIMIT 1")->fetchColumn();
                $userStmt = $this->pdo->prepare('INSERT INTO users (username, email, password, nama, role_id, status) VALUES (:username, :email, :password, :nama, :role_id, 1)');
                $userStmt->execute(['username' => $data['username'], 'email' => $data['email'], 'password' => password_hash($data['password'], PASSWORD_DEFAULT), 'nama' => $data['nama'], 'role_id' => $roleId]);
                $userId = (int)$this->pdo->lastInsertId();
                $dosenStmt = $this->pdo->prepare('INSERT INTO dosen (nidn, nama, email, no_hp, program_studi_id, status, user_id) VALUES (:nidn, :nama, :email, :no_hp, :program_studi_id, 1, :user_id)');
                $dosenStmt->execute(['nidn' => $data['nidn'], 'nama' => $data['nama'], 'email' => $data['email'], 'no_hp' => $data['no_hp'], 'program_studi_id' => $data['program_studi_id'], 'user_id' => $userId]);
                $this->pdo->commit();
                flash('success', 'Pendaftaran dosen berhasil. Silakan masuk menggunakan akun baru Anda.');
                redirect('/login');
            } catch (Throwable $e) {
                if ($this->pdo->inTransaction()) $this->pdo->rollBack();
                flash('error', 'Pendaftaran gagal. Username, email, atau NIDN mungkin sudah digunakan.');
                view('auth/register-dosen', ['pageTitle' => 'Pendaftaran Dosen', 'programStudi' => $programStudi, 'form' => $data]);
            }
            return;
        }

        view('auth/register-dosen', ['pageTitle' => 'Pendaftaran Dosen', 'programStudi' => $programStudi, 'form' => []]);
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        verify_csrf();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool)$params['secure'], (bool)$params['httponly']);
        }
        session_destroy();
        redirect('/login');
    }

    private function validateRegistration(array $data): ?string
    {
        if ($data['nama'] === '' || $data['nim'] === '' || $data['username'] === '' || $data['email'] === '' || $data['program_studi_id'] <= 0 || $data['angkatan'] < 2000) {
            return 'Semua data wajib diisi dengan lengkap.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Format email belum valid.';
        }

        if (!preg_match('/^[A-Za-z0-9._-]{4,40}$/', $data['username'])) {
            return 'Username hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda hubung.';
        }

        if (strlen($data['password']) < 8) {
            return 'Password minimal 8 karakter.';
        }

        if ($data['password'] !== $data['password_confirmation']) {
            return 'Konfirmasi password tidak sama.';
        }

        if (!(new ProgramStudiModel($this->pdo))->findById($data['program_studi_id'])) {
            return 'Program studi tidak ditemukan.';
        }

        return null;
    }

    private function validateStaffRegistration(array $data, string $identityLabel): ?string
    {
        if ($data['nama'] === '' || $data['nidn'] === '' || $data['username'] === '' || $data['email'] === '' || $data['program_studi_id'] <= 0) return 'Semua data wajib diisi dengan lengkap.';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) return 'Format email belum valid.';
        if (!preg_match('/^[A-Za-z0-9._-]{4,40}$/', $data['username'])) return 'Format username tidak valid.';
        if (strlen($data['password']) < 8) return 'Password minimal 8 karakter.';
        if ($data['password'] !== $data['password_confirmation']) return 'Konfirmasi password tidak sama.';
        if (!(new ProgramStudiModel($this->pdo))->findById($data['program_studi_id'])) return 'Program studi tidak ditemukan.';
        return null;
    }

    private function redirectByRole(string $role): void
    {
        $role = strtolower($role);

        if ($role === 'admin') {
            redirect('/admin/dashboard');
        }

        if ($role === 'dosen') {
            redirect('/dosen/dashboard');
        }

        redirect('/mahasiswa/dashboard');
    }
}
