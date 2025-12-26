<?php

namespace App\Adapters\Http;

use App\Services\CatalogService;

class CatalogController
{
    public function __construct(
        private CatalogService $catalogService
    ) {}

    /**
     * Function to add item to catalog
     * @param array $userData
     * @param array|null $input
     * @return array
     */
    public function addItem(array $userData, ?array $input) : array
    {
        if (!$input || !isset($input['household_id']) || !isset($input['canonical_name']) || !isset($input['category'])) {
            return [
                'status' => 400,
                'data' => ['error' => 'Invalid input']
            ];
        }

        try {

            $this->catalogService->addItem(
                (int)$input['household_id'],
                $input['canonical_name'],
                $input['category']
            );

            return [
                'status' => 201,
                'data' => [
                    'message' => 'Item added successfully'
                ]
            ];

        } catch (\Exception $e) {
            return [
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ];
        }
    }

    // function getCatalog goes here
    public function getCatalog(int $householdId) : array
    {
        try {

            $items = $this->catalogService->getItemsByHousehold(
                (int)$householdId
            );

            return [
                'status' => 200,
                'data' => [
                    'items' => $items
                ]
            ];

        } catch (\Exception $e) {
            return [
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ];
        }
    }
}