<?php

namespace App\Api\Studies\Requirements;

/**
 * Represents a preset of requirements that may be set up for the study
 */
class StudyRequirement
{
    public const REQUIREMENT_AGE = 'age';
    public const REQUIREMENT_FREQUENCY = 'frequency';
    public const REQUIREMENT_DIAGNOSIS = 'diagnosis';
    public const REQUIREMENT_SPECIAL_DIET = 'specialDiet';
    // etc ...

    public const RULE_MINIMUM = 'min';
    public const RULE_MAXIMUM = 'max';
    public const RULE_EQUAL = 'equal';
    // etc ...

    public static function getAllRequirements(): array
    {
        return [
            self::REQUIREMENT_AGE,
            self::REQUIREMENT_FREQUENCY,
            self::REQUIREMENT_DIAGNOSIS,
            self::REQUIREMENT_SPECIAL_DIET,
        ];
    }

    public static function requirementExists(string $value): bool
    {
        return in_array($value, self::getAllRequirements());
    }

    public static function getAllRules(): array
    {
        return [
            self::RULE_MINIMUM,
            self::RULE_MAXIMUM,
            self::RULE_EQUAL,
        ];
    }

    public static function ruleExists(string $value): bool
    {
        return in_array($value, self::getAllRules());
    }
}
