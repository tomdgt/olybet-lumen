<?php

namespace Olybet\Tests\Functional;

use Olybet\Tests\Data\FemalePersonalCodes;
use Olybet\Tests\Data\MalePersonalCodes;
use TestCase;

class PersonalCodeControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @param string $birthDateString
     * @param string $sex
     * @param int $expectedStatus
     * @param array $expectedResponse
     * @return void
     * @dataProvider generateCodesProvider
     */
    public function testGenerateCodes(
        string $birthDateString,
        string $sex,
        int $expectedStatus,
        array $expectedResponse
    ) {
        $this->get("/api/generate/{$birthDateString}/{$sex}");

        $this->assertResponseStatus($expectedStatus);

        $this->assertJsonStringEqualsJsonString($this->response->getContent(), json_encode($expectedResponse));
    }

    public function generateCodesProvider(): array
    {
        return [
            'XIX century male' => [
                '1850-02-02',
                '1',
                200,
                ['codes' => MalePersonalCodes::XIX_1850_02_02],
            ],
            'XIX century female' => [
                '1850-02-02',
                '0',
                200,
                ['codes' => FemalePersonalCodes::XIX_1850_02_02],
            ],
            'XX century male' => [
                '1950-02-02',
                '1',
                200,
                ['codes' => MalePersonalCodes::XX_1950_02_02],
            ],
            'XX century female' => [
                '1950-02-02',
                '0',
                200,
                ['codes' => FemalePersonalCodes::XX_1950_02_02],
            ],
            'XXI century male' => [
                '2050-02-02',
                '1',
                200,
                ['codes' => MalePersonalCodes::XXI_2050_02_02],
            ],
            'XXI century female' => [
                '2050-02-02',
                '0',
                200,
                ['codes' => FemalePersonalCodes::XXI_2050_02_02],
            ],
            'XXII century male' => [
                '2150-02-02',
                '1',
                200,
                ['codes' => MalePersonalCodes::XXII_2150_02_02],
            ],
            'XXII century female' => [
                '2150-02-02',
                '0',
                200,
                ['codes' => FemalePersonalCodes::XXII_2150_02_02],
            ],
            'Invalid sex' => [
                '2050-02-02',
                'A',
                400,
                ['error' => 'Invalid request',],
            ],
            'Invalid date' => [
                '205A-02-02',
                '1',
                400,
                ['error' => 'Invalid request',],
            ],
            'Date too early' => [
                '1750-02-02',
                '1',
                400,
                ['error' => 'Invalid request',],
            ],
            'Date too late' => [
                '2250-02-02',
                '1',
                400,
                ['error' => 'Invalid request',],
            ],
        ];
    }

    /**
     * A basic test example.
     *
     * @param string $code
     * @param int $expectedStatus
     * @param array $expectedResponse
     * @return void
     * @dataProvider validateCodeProvider
     */
    public function testValidateCode(
        string $code,
        int $expectedStatus,
        array $expectedResponse
    ) {
        $this->get("/api/validate/{$code}");

        $this->assertResponseStatus($expectedStatus);

        $this->assertJsonStringEqualsJsonString($this->response->getContent(), json_encode($expectedResponse));
    }

    public function validateCodeProvider(): array
    {
        return [
            'Valid regular male' => [
                '38902020059',
                200,
                ['valid' => true],
            ],
            'Valid day exemption male' => [
                '38902000056',
                200,
                ['valid' => true],
            ],
            'Valid month exemption male' => [
                '38900020059',
                200,
                ['valid' => true],
            ],
            'Valid day and month exemption male' => [
                '38900000057',
                200,
                ['valid' => true],
            ],
            'Valid regular female' => [
                '48902020054',
                200,
                ['valid' => true],
            ],
            'Valid day exemption female' => [
                '48902000057',
                200,
                ['valid' => true],
            ],
            'Valid month exemption female' => [
                '48900020050',
                200,
                ['valid' => true],
            ],
            'Valid day and month exemption female' => [
                '48900000058',
                200,
                ['valid' => true],
            ],
            'Valid starts with 9 exemption' => [
                '91247567814',
                200,
                ['valid' => true],
            ],

            'Invalid wrong first digit' => [
                '08900000058',
                200,
                ['valid' => false],
            ],
            'Invalid contains non-numeric' => [
                '489020A0054',
                200,
                ['valid' => false],
            ],
            'Invalid wrong control' => [
                '48902000058',
                200,
                ['valid' => false],
            ],
            'Invalid too short' => [
                '489020000577',
                200,
                ['valid' => false],
            ],
            'Invalid too long' => [
                '4890200057',
                200,
                ['valid' => false],
            ],
        ];
    }
}
