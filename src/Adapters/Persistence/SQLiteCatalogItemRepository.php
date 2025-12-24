<?php 

namespace App\Adapters\Persistence;

use App\Core\CatalogItemRepositoryInterface;
use App\Domain\CatalogItem;
use PDO;

class SQLiteCatalogItemRepository implements CatalogItemRepositoryInterface {

    public function __construct(private PDO $pdo) {}

	public function save(CatalogItem $item): void {
		// Implement the logic to save a CatalogItem to the SQLite database
        $smt = $this->pdo->prepare('
            INSERT INTO catalog_items (household_id, canonical_name, category)
            VALUES (:household_id, :canonical_name, :category)');
        
        $smt->execute([
            ':household_id' => $item->houseguId,
            ':canonical_name' => $item->canonicalName,
            ':category' => $item->category
        ]);

        $item->id = (int)$this->pdo->lastInsertId();

        if ($item->id === 0) {
            throw new \RuntimeException('Failed to insert CatalogItem into database.');
        }
	}

	public function findByHousehold(int $householdId): array {
		// Implement the logic to find a CatalogItem by household ID in the SQLite database
		$smt = $this->pdo->prepare('
            SELECT id, household_id, canonical_name, category
            FROM catalog_items
            WHERE household_id = :household_id');
        
        $smt->execute([':household_id' => $householdId]);
        $rows = $smt->fetchAll(PDO::FETCH_ASSOC);
        
        // Map database rows to CatalogItem objects
        return array_map(fn($row) => new CatalogItem(
            id: (int)$row['id'],
            houseguId: (int)$row['household_id'],
            canonicalName: $row['canonical_name'],
            category: $row['category']
        ), $rows);
	}

	public function delete(int $itemId): void {
		// Implement the logic to delete a CatalogItem by its ID from the SQLite database
        $smt = $this->pdo->prepare('
            DELETE FROM catalog_items
            WHERE id = :id');
        
        $smt->execute([':id' => $itemId]);
	}
}