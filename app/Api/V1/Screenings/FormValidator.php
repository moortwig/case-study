<?php

namespace App\Api\V1\Screenings;

use App\Exceptions\MissingCaseException;
use App\Exceptions\ValidationException;
use Illuminate\Support\Carbon;

class FormValidator
{
    /**
     * @throws MissingCaseException|ValidationException
     */
    public function validate($formValues): void
    {
        $dependantType = null;
        foreach ($formValues as $fieldValues) {
            $this->validateField($fieldValues);
            if ('frequency' === $fieldValues['validationType'] && 'daily' === $fieldValues['value']) {
                $dependantType = 'daily-frequency';
            }
        }

        $this->validateDailyFrequency($dependantType, $formValues);
    }

    /**
     * @throws MissingCaseException|ValidationException
     */
    public function validateField(array $fieldValues): void
    {
        if (isset($fieldValues['required']) && $fieldValues['required'] && !$fieldValues['value']) {
            throw new ValidationException(sprintf('The required field \'%s\' is missing', $fieldValues['label']));
        }

        if (!isset($fieldValues['validationType'])) {
            return;
        }
        $type = $fieldValues['validationType'];
        switch ($type) {
            case 'name':
                $this->validateName($fieldValues['value']);
                break;
            case 'year':
                $this->validateYear($fieldValues['value']);
                break;
            case 'month':
                $this->validateMonth($fieldValues['value']);
                break;
            case 'day':
                $this->validateDay($fieldValues['value']);
                break;
            case 'frequency':
            case 'daily-frequency':
                break;
            default:
                throw new MissingCaseException(sprintf('No case set up for validation type \'%s\'', $type));
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateName(string $value): void
    {
        if (mb_strlen($value) < 2) {
            throw new ValidationException('Name should be at least 2 characters');
        }

        if (mb_strlen($value) > 30) {
            throw new ValidationException('Name is too long');
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateYear(int $value): void
    {
        if (!$value) {
            throw new ValidationException('Please review the Year field');
        }

        $now = Carbon::now()->format('Y');
        if ($value < 1900 || $value > $now) {
            throw new ValidationException('Year is outside acceptable range');
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateMonth(int $value): void
    {
        if (!$value) {
            throw new ValidationException('Please review the Month field');
        }

        if ($value < 1 || $value > 12) {
            throw new ValidationException('Month is outside acceptable range');
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateDay(int $value): void
    {
        if (!$value) {
            throw new ValidationException('Please review the Day field');
        }

        if ($value < 1 || $value > 31) {
            throw new ValidationException('Day is outside acceptable range');
        }
    }

    public function validateDailyFrequency($dependantType, array $formValues): void
    {
        if (!$dependantType) {
            return;
        }

        $validationTypes = array_column($formValues, 'validationType');
        if (!in_array($dependantType, $validationTypes)) {
            throw new ValidationException('The required field \'Daily Frequency\' is missing');
        }
    }
}
