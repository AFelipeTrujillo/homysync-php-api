<?php

namespace App\Adapters\Persistence;

use App\Core\HouseholdRepositoryInterface;
use App\Domain\Household;
use PDO;

class SQLiteHouseholdRepository implements HouseholdRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}
    
    public function create(Household $household): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO households (name, timezone, created_at) 
            VALUES (:name, :timezone, :created_at)');

        $stmt->execute([
            ':name' => $household->name,
            ':timezone' => $household->timezone,
            ':created_at' => $household->cretedAt ?: time(),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function addMember(int $householdId, int $userId): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO household_members (household_id, user_id) 
            VALUES (:household_id, :user_id)');

        $stmt->execute([
            ':household_id' => $householdId,
            ':user_id' => $userId,
        ]);
    }

    public function getUserHouseholds(int $userId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT h.id, h.name, h.timezone, h.created_at 
            FROM households h
            JOIN household_members hm ON h.id = hm.household_id
            WHERE hm.user_id = :user_id');

        $stmt->execute([':user_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $households = [];
        foreach ($rows as $row) {
            $households[] = new Household(
                id: (int)$row['id'],
                name: $row['name'],
                timezone: $row['timezone'],
                cretedAt: (int)$row['created_at']
            );
        }

        return $households;
    }

    public function findById(int $householdId): ?Household
    {
        $stmt = $this->pdo->prepare('
            SELECT id, name, timezone, created_at 
            FROM households 
            WHERE id = :id');

        $stmt->execute([':id' => $householdId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Household(
                id: (int)$row['id'],
                name: $row['name'],
                timezone: $row['timezone'],
                cretedAt: (int)$row['created_at']
            );
        }

        return null;
    }

    public function update(Household $household): void
    {
        $stmt = $this->pdo->prepare('
            UPDATE households 
            SET name = :name, timezone = :timezone 
            WHERE id = :id');

        $stmt->execute([
            ':name' => $household->name,
            ':timezone' => $household->timezone,
            ':id' => $household->id,
        ]);
    }
}