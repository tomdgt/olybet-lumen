<?php

namespace App\Services\PersonalCode;

use App\Exceptions\PersonalCodeGeneratorException;
use Carbon\Carbon;

class PersonalCodeGeneratorService
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
     * @param Carbon $birthDate
     * @param bool $isMale
     * @param int $serial
     *
     * @return int
     * @throws PersonalCodeGeneratorException
     */
    public function generate(Carbon $birthDate, bool $isMale = false, int $serial = 0): int
    {
        $dateString = $birthDate->format('Ymd');
        $firstDigit = $this->generateFirstDigit(substr($dateString, 0, 2), $isMale);

        $code = $firstDigit . substr($dateString, 2) . sprintf('%03d', $serial);

        $control = $this->personalCodeControlService->calculateControlNumber($code);

        return $code . $control;
    }

    /**
     * @param string $firstYearDigits
     * @param bool $isMale
     *
     * @return string
     * @throws PersonalCodeGeneratorException
     */
    private function generateFirstDigit(string $firstYearDigits, bool $isMale = false): string
    {
        $firstDigits = [
            '18' => 1,
            '19' => 3,
            '20' => 5,
            '21' => 7,
        ];

        if (!isset($firstDigits[$firstYearDigits])) {
            throw new PersonalCodeGeneratorException('Invalid first year digits');
        }

        $firstDigit = $firstDigits[$firstYearDigits];

        return $isMale ? $firstDigit : $firstDigit + 1;
    }
}
