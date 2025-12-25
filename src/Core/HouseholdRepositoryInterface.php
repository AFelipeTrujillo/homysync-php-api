<?php

namespace App\Core;

use App\Domain\Household;

interface HouseholdRepositoryInterface {
    public function create(Household $household): int;
    public function addMember(int $householdId, int $userId): void;
    public function getUserHouseholds(int $userId): array;
}