<?php

declare(strict_types=1);

namespace App\Models;

class User extends Model
{
    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => strtolower($email)]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO users (name, email, password, role, status, created_at, updated_at)
             VALUES (:name, :email, :password, :role, :status, NOW(), NOW())'
        );
        $statement->execute([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'user',
            'status' => $data['status'] ?? 'active',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function all(): array
    {
        $statement = $this->db->prepare('SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC');
        $statement->execute();

        return $statement->fetchAll();
    }

    public function count(): int
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM users');
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function updateAdmin(int $id, string $role, string $status): void
    {
        $statement = $this->db->prepare('UPDATE users SET role = :role, status = :status, updated_at = NOW() WHERE id = :id');
        $statement->execute([
            'id' => $id,
            'role' => $role,
            'status' => $status,
        ]);
    }
}
