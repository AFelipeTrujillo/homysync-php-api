<?php

namespace App\Domain;

class Categories {
    const CategoryDairy = "dairy";
    const CategoryProduce = "produce";
    const CategoryMeat = "meat";
    const CategoryGrains = "grains";
    const CategoryPantry    = "pantry";
	const CategoryBeverages = "beverages";
	const CategoryCleaning  = "cleaning";
	const CategoryFrozen    = "frozen";
	const CategoryPersonal  = "personal";
	const CategoryOther     = "other";

    public static function listAllCategories(): array {
        return [
            self::CategoryDairy,
            self::CategoryProduce,
            self::CategoryMeat,
            self::CategoryGrains,
            self::CategoryPantry,
            self::CategoryBeverages,
            self::CategoryCleaning,
            self::CategoryFrozen,
            self::CategoryPersonal,
            self::CategoryOther
        ];
    }

    // Function to validate if a category is valid
    public static function isValidCategory(string $category): bool {
        return in_array($category, self::listAllCategories());
    }
    
}