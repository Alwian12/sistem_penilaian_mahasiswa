<?php

class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsernameOrEmail(string $identifier): ?array
    {
        $sql = 'SELECT u.*, r.nama_role AS role_name
                FROM users u
                LEFT JOIN roles r ON r.id = u.role_id
                WHERE u.username = :username OR u.email = :email
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'username' => $identifier,
            'email' => $identifier,
        ]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getUserById(int $id): ?array
    {
        $sql = 'SELECT u.*, r.nama_role AS role_name
                FROM users u
                LEFT JOIN roles r ON r.id = u.role_id
                WHERE u.id = :id
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function getRoleLabel(string $role): string
    {
        $map = [
            'admin' => 'Admin',
            'dosen' => 'Dosen',
            'mahasiswa' => 'Mahasiswa',
        ];

        return $map[$role] ?? ucfirst($role);
    }
}
