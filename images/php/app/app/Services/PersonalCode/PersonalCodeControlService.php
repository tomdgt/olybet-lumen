<?php

namespace App\Services\PersonalCode;

class PersonalCodeControlService
{
    /**
     * @param string $code
     * @return int
     */
    public function calculateControlNumber(string $code): int
    {
        $control = $this->calculatePrimary($code);

        if ($control !== 10) {
            return $control;
        }

        $control = $this->calculateSecondary($code);

        if ($control !== 10) {
            return $control;
        }

        return 0;
    }

    private function calculatePrimary(string $code): int
    {
        $sum = array_sum([
            $code[0] * 1,
            $code[1] * 2,
            $code[2] * 3,
            $code[3] * 4,
            $code[4] * 5,
            $code[5] * 6,
            $code[6] * 7,
            $code[7] * 8,
            $code[8] * 9,
            $code[9] * 1,
        ]);

        return $sum % 11;
    }

    private function calculateSecondary(string $code): int
    {
        $sum = array_sum([
            $code[0] * 3,
            $code[1] * 4,
            $code[2] * 5,
            $code[3] * 6,
            $code[4] * 7,
            $code[5] * 8,
            $code[6] * 9,
            $code[7] * 1,
            $code[8] * 2,
            $code[9] * 3,
        ]);

        return $sum % 11;
    }
}
