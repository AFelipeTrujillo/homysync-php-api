<?php 

namespace App\Adapters\Persistence;

use App\Core\UserRepositoryInterface;
use App\Domain\User;
use PDO;

class SQLiteUserRepository implements UserRepositoryInterface {

    public function __construct(private PDO $pdo) {}

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO users (email, password_hash, name) 
            VALUES (:email, :password_hash, :name)');

        $stmt->execute([
            ':email' => $user->email,
            ':password_hash' => $user->passwordHash,
            ':name' => $user->name,
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT id, email, password_hash, name FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User(
                id: (int)$row['id'],
                email: $row['email'],
                passwordHash: $row['password_hash'],
                name: $row['name']
            );
        }

        return null;
    }
}