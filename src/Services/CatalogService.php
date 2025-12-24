<?php

namespace App\Services;

use App\Core\CatalogItemRepositoryInterface;
use App\Domain\CatalogItem;
use App\Domain\Categories;
use Exception;

class CatalogService {
    
    public function __construct(
        private CatalogItemRepositoryInterface $catalogItemRepository
    ) {}

    /** 
     * Function to add item to catalog
     * @param int $householdId
     * @param string $canonicalName
     * @param string $category
     * @throws Exception
     * @return void
     */
    public function addItemToCatalog(int $householdId, string $canonicalName, string $category): void {
        
        // Validate canonical name
        if (empty(trim($canonicalName))) {
            throw new Exception("Canonical name cannot be empty.");
        }

        // Validate category
        if (!Categories::isValidCategory($category)) {
            throw new Exception("Invalid category: $category");
        }

        $item = new CatalogItem(
            id: null,
            houseguId: $householdId,
            canonicalName: $canonicalName,
            category: $category
        );

        $this->catalogItemRepository->save($item);
    }

    /** 
     * Function get items by household ID
     * @param int $householdId
     * @return CatalogItem[]
     */
    function getItemsByHousehold(int $householdId): array {
        return $this->catalogItemRepository->findByHousehold($householdId);
    }
}