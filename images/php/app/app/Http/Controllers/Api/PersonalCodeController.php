<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\PersonalCodeGeneratorException;
use App\Exceptions\PersonalCodeValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalCodeGenerateRequest;
use App\Services\PersonalCode\PersonalCodeGeneratorService;
use App\Services\PersonalCode\PersonalCodeValidatorService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class PersonalCodeController extends Controller
{
    /**
     * @var PersonalCodeGeneratorService
     */
    private $personalCodeGeneratorService;

    /**
     * @var PersonalCodeValidatorService
     */
    private $personalCodeValidatorService;

    public function __construct(
        PersonalCodeGeneratorService $personalCodeGeneratorService,
        PersonalCodeValidatorService $personalCodeValidatorService
    ) {
        $this->personalCodeGeneratorService = $personalCodeGeneratorService;
        $this->personalCodeValidatorService = $personalCodeValidatorService;
    }

    public function validateCode(string $personalCode)
    {
        try {
            $isValid = $this->personalCodeValidatorService->validate($personalCode);
        } catch (PersonalCodeValidationException $e) {
            return response()->json([
                'valid' => false,
            ]);
        }

        return response()->json([
            'valid' => $isValid,
        ]);
    }

    public function generateCodes(PersonalCodeGenerateRequest $request, string $birthDateString, string $sex)
    {
        /** @var Carbon $birthDate */
        $birthDate = Carbon::createFromFormat('!Y-m-d', $birthDateString);

        $isMale = 1 == $sex;

        $codes = [];

        for ($i = 0; $i < 1000; $i++) {
            try {
                $codes[] = $this->personalCodeGeneratorService->generate($birthDate, $isMale, $i);
            } catch (PersonalCodeGeneratorException $e) {
                response()->json(['error' => 'Invalid request',], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        return response()->json([
            'codes' => $codes,
        ]);
    }
}
