<?php

namespace App\Adapters\Http;

use App\Services\HouseholdService;
use Exception;

class HouseholdController 
{
    public function __construct(
        private HouseholdService $householdService
    ) {}
    
    public function create(array $userData, ?array $input) : array
    {
        if (!$input || !isset($input['name']) || !isset($input['timezone'])) {
            return [
                'status' => 400,
                'data' => ['error' => 'Invalid input']
            ];
        }

        $timezone = $input['timezone'] ?? 'UTC';

        try {

            $householdId = $this->householdService->createNewHousehold(
                name: $input['name'],
                timezone: $timezone,
                createdAt: time(),
                ownerId: $userData['sub']
            );

            return [
                'status' => 201,
                'data' => [
                    'message' => 'Household created successfully',
                    'household_id' => $householdId
                ]
            ];

        } catch (Exception $e) {
            return [
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ];
        }
        return [];
    }

    public function update(array $userData, ?array $input) : array
    {
        if(!$input || !isset($input['household_id']) || !isset($input['name']) || !isset($input['timezone'])) {
            return [
                'status' => 400,
                'data' => ['error' => 'Invalid input']
            ];
        }

        try {

            $this->householdService->updateHousehold(
                householdId: (int)$input['household_id'],
                name: $input['name'],
                timezone: $input['timezone']
            );

            return [
                'status' => 200,
                'data' => [
                    'message' => 'Household updated successfully'
                ]
            ];

        } catch (Exception $e) {
            return [
                'status' => 500,
                'data' => ['error' => $e->getMessage()]
            ];
        }
        return [];
    }
}