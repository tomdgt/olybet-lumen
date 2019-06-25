<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class PersonalCodeGenerateRequest extends EnhancedRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'birth_date_string' => 'date_format:Y-m-d|after:1799-12-31|before:2200-01-01',
            'sex' => 'in:0,1',
        ];
    }

    protected function validationData(): array
    {
        return array_merge($this->request->all(), [
            'birth_date_string' => $this->getRouteParameter('birthDateString'),
            'sex' => $this->getRouteParameter('sex'),
        ]);
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json(['error' => 'Invalid request',], JsonResponse::HTTP_BAD_REQUEST)
        );
    }
}
