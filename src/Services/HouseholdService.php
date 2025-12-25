<?php

namespace App\Services;

use App\Core\HouseholdRepositoryInterface;
use App\Domain\Household;

class HouseholdService
{
    public function __construct(
        private HouseholdRepositoryInterface $householdRepository
    ) {}

    public function createNewHousehold(string $name, string $timezone, int $createdAt, int $ownerId): int
    {
        $householdId = $this->householdRepository->create(
            new Household(
                id: null,
                name: $name,
                timezone: $timezone,
                cretedAt: $createdAt
            )
        );

        $this->householdRepository->addMember($householdId, $ownerId);

        return $householdId;
    }
}