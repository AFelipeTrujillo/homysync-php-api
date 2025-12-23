<?php

namespace  App\Domain;

class Units {
    const UnitPiece         = "unit";
    const UnitKilogram      = "kg";
    const UnitGram          = "g";
    const UnitLiters        = "l";
    const UnitMilliliter    = "ml";
    const UnitTablespoon    = "tbsp";

    public static function listAllUnits(): array {
        return [
            self::UnitPiece,
            self::UnitKilogram,
            self::UnitGram,
            self::UnitLiters,
            self::UnitMilliliter,
            self::UnitTablespoon
        ];
    }

    // Function to validate if a unit is valid
    public static function isValidUnit(string $unit): bool {
        return in_array($unit, self::listAllUnits());
    }
    
}