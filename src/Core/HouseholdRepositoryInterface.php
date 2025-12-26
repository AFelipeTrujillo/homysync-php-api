<?php

namespace App\Core;

use App\Domain\Household;

interface HouseholdRepositoryInterface {
    public function create(Household $household): int;
    public function findById(int $householdId): ?Household;
    public function addMember(int $householdId, int $userId): void;
    public function getUserHouseholds(int $userId): array;
    public function update(Household $household): void;
}