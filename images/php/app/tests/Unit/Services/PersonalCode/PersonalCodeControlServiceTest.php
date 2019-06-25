<?php

namespace Olybet\Tests\Unit\Services\PersonalCode;

use function app;
use App\Services\PersonalCode\PersonalCodeControlService;
use TestCase;

class PersonalCodeControlServiceTest extends TestCase
{
    /**
     * @param $code
     * @param $expectedControl
     *
     * @dataProvider calculateControlNumberProvider
     */
    public function testCalculateControlNumber(string $code, int $expectedControl)
    {
        /** @var PersonalCodeControlService $service */
        $service = app(PersonalCodeControlService::class);

        $this->assertEquals($expectedControl, $service->calculateControlNumber($code));
    }

    public function calculateControlNumberProvider(): array
    {
        return [
            'male regular' => [
                '3890202020',
                0,
            ],
            'male day exemption' => [
                '3890200020',
                8,
            ],
            'male month exemption' => [
                '3890002020',
                1,
            ],
            'male day and month exemption' => [
                '3890000020',
                9,
            ],
            'female regular' => [
                '4890202020',
                1,
            ],
            'female day exemption' => [
                '4890200020',
                9,
            ],
            'female month exemption' => [
                '4890002020',
                2,
            ],
            'female day and month exemption' => [
                '4890000020',
                5,
            ],
        ];
    }
}
