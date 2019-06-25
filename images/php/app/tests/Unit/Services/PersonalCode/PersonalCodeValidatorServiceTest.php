<?php

namespace Olybet\Tests\Unit\Services\PersonalCode;

use function app;
use App\Exceptions\PersonalCodeValidationException;
use App\Services\PersonalCode\PersonalCodeControlService;
use App\Services\PersonalCode\PersonalCodeValidatorService;
use Mockery;
use TestCase;

class PersonalCodeValidatorServiceTest extends TestCase
{
    /**
     * @param string $code
     * @param int $controlNumber
     * @param string|null $error
     * @dataProvider validateProvider
     */
    public function testValidate(string $code, int $controlNumber = null, string $error = null)
    {
        $mockControlService = Mockery::mock(PersonalCodeControlService::class);
        if (null !== $controlNumber) {
            $mockControlService->shouldReceive('calculateControlNumber')
                ->with(substr($code, 0, -1))
                ->once()
                ->andReturn($controlNumber);
        } else {
            $mockControlService->shouldNotReceive('calculateControlNumber');
        }

        $this->app->instance(PersonalCodeControlService::class, $mockControlService);

        /** @var PersonalCodeValidatorService $service */
        $service = app(PersonalCodeValidatorService::class);

        try {
            $this->assertTrue($service->validate($code));
        } catch (PersonalCodeValidationException $e) {
            $this->assertEquals($error, $e->getMessage());
        }
    }

    public function validateProvider(): array
    {
        return [
            'Valid: regular male' => [
                '38902020059',
                9,
            ],
            'Valid: day exemption male' => [
                '38902000056',
                6,
            ],
            'Valid: month exemption male' => [
                '38900020059',
                9,
            ],
            'Valid: day and month exemption male' => [
                '38900000057',
                7,
            ],
            'Valid: regular female' => [
                '48902020054',
                4,
            ],
            'Valid: day exemption female' => [
                '48902000057',
                7,
            ],
            'Valid: month exemption female' => [
                '48900020050',
                0,
            ],
            'Valid: day and month exemption female' => [
                '48900000058',
                8,
            ],
            'Valid: starts with 9 exemption' => [
                '91247567814',
            ],

            'Invalid: wrong first digit' => [
                '08900000058',
                null,
                'Invalid first digit'
            ],
            'Invalid: contains non-numeric' => [
                '489020A0054',
                null,
                'Code contains non-digits'
            ],
            'Invalid: wrong control' => [
                '48902000058',
                9,
                'Invalid control number',
            ],
            'Invalid: too long' => [
                '489020000577',
                null,
                'Code too long'
            ],
            'Invalid: too short' => [
                '4890200057',
                null,
                'Code too short'
            ],
        ];
    }
}
