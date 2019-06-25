<?php

namespace App\Services\PersonalCode;

use App\Exceptions\PersonalCodeValidationException as PersonalCodeValidationExceptionAlias;

class PersonalCodeValidatorService
{
    /**
     * @var PersonalCodeControlService
     */
    private $personalCodeControlService;

    public function __construct(PersonalCodeControlService $personalCodeControlService)
    {
        $this->personalCodeControlService = $personalCodeControlService;
    }

    /**
     * @param string $personalCode
     * @return bool
     * @throws PersonalCodeValidationExceptionAlias
     */
    public function validate(string $personalCode): bool
    {
        $this->validateStructure($personalCode);

        if (9 === (int)$personalCode[0]) {
            return true;
        }

        $this->validateFirstDigit($personalCode[0]);

        if (!$this->hasMonthOrDayException($personalCode)) {
            $this->validateBirthDate($personalCode);
        }

        $this->validateControlNumber($personalCode);

        return true;
    }

    /**
     * @param string $personalCode
     * @throws PersonalCodeValidationExceptionAlias
     */
    private function validateStructure(string $personalCode)
    {
        if (!ctype_digit($personalCode)) {
            throw new PersonalCodeValidationExceptionAlias('Code contains non-digits');
        }

        if (strlen($personalCode) > 11) {
            throw new PersonalCodeValidationExceptionAlias('Code too long');
        }

        if (strlen($personalCode) < 11) {
            throw new PersonalCodeValidationExceptionAlias('Code too short');
        }
    }

    /**
     * @param int $firstDigit
     * @throws PersonalCodeValidationExceptionAlias
     */
    private function validateFirstDigit(int $firstDigit)
    {
        if (0 === $firstDigit) {
            throw new PersonalCodeValidationExceptionAlias('Invalid first digit');
        }
    }

    /**
     * @param string $personalCode
     * @return bool
     */
    private function hasMonthOrDayException(string $personalCode): bool
    {
        $monthSection = substr($personalCode, 3, 2);
        $daySection = substr($personalCode, 5, 2);

        if ('00' === $monthSection || '00' === $daySection) {
            return true;
        }

        return false;
    }

    /**
     * @param string $personalCode
     * @throws PersonalCodeValidationExceptionAlias
     */
    private function validateBirthDate(string $personalCode)
    {
        $firstYearDigits = $this->firstDigitToFirstYearDigits($personalCode[0]);

        $year = $firstYearDigits . substr($personalCode, 1, 2);
        $month = substr($personalCode, 3, 2);
        $day = substr($personalCode, 5, 2);

        if (!checkdate($month, $day, $year)) {
            throw new PersonalCodeValidationExceptionAlias('Birthdate is invalid');
        }
    }

    /**
     * @param int $firstDigit
     *
     * @return string
     */
    private function firstDigitToFirstYearDigits(int $firstDigit): string
    {
        $firstYearDigits = [
            1 => '18',
            2 => '18',
            3 => '19',
            4 => '19',
            5 => '20',
            6 => '20',
            7 => '21',
            8 => '21',
        ];

        return $firstYearDigits[$firstDigit];
    }

    /**
     * @param $personalCode
     * @throws PersonalCodeValidationExceptionAlias
     */
    private function validateControlNumber($personalCode)
    {
        $control = $this->personalCodeControlService->calculateControlNumber(substr($personalCode, 0, 10));

        if ($control !== (int)$personalCode[10]) {
            throw new PersonalCodeValidationExceptionAlias('Invalid control number');
        }
    }
}
