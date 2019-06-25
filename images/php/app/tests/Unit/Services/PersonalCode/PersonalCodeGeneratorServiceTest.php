<?php

namespace Olybet\Tests\Unit\Services\PersonalCode;

use function app;
use App\Exceptions\PersonalCodeGeneratorException;
use App\Services\PersonalCode\PersonalCodeControlService;
use App\Services\PersonalCode\PersonalCodeGeneratorService;
use Carbon\Carbon;
use Mockery;
use TestCase;

class PersonalCodeGeneratorServiceTest extends TestCase
{
    /**
     * @param Carbon $date
     * @param bool $isMale
     * @param int $serial
     * @param string $expectedCode
     * @param int $controlNumber
     * @param string $error
     * @dataProvider generateProvider
     */
    public function testGenerate(
        Carbon $date,
        bool $isMale,
        int $serial,
        string $expectedCode,
        int $controlNumber = null,
        string $error = ''
    ) {
        $mockControlService = Mockery::mock(PersonalCodeControlService::class);
        if (null !== $controlNumber) {
            $mockControlService->shouldReceive('calculateControlNumber')
                ->with(substr($expectedCode, 0, -1))
                ->once()
                ->andReturn($controlNumber);
        } else {
            $mockControlService->shouldNotReceive('calculateControlNumber');
        }

        $this->app->instance(PersonalCodeControlService::class, $mockControlService);

        /** @var PersonalCodeGeneratorService $service */
        $service = app(PersonalCodeGeneratorService::class);

        try {
            $this->assertEquals($expectedCode, $service->generate($date, $isMale, $serial));
        } catch (PersonalCodeGeneratorException $e) {
            $this->assertEquals($error, $e->getMessage());
        }
    }

    public function generateProvider(): array
    {
        return [
            'XIX century male' => [
                Carbon::createFromDate(1850, 2, 2),
                true,
                50,
                '15002020503',
                3
            ],
            'XIX century female' => [
                Carbon::createFromDate(1850, 2, 2),
                false,
                50,
                '25002020504',
                4
            ],
            'XX century male' => [
                Carbon::createFromDate(1950, 2, 2),
                true,
                50,
                '35002020505',
                5
            ],
            'XX century female' => [
                Carbon::createFromDate(1950, 2, 2),
                false,
                50,
                '45002020506',
                6
            ],
            'XXI century male' => [
                Carbon::createFromDate(2050, 2, 2),
                true,
                50,
                '55002020507',
                7
            ],
            'XXI century female' => [
                Carbon::createFromDate(2050, 2, 2),
                false,
                50,
                '65002020508',
                8
            ],
            'XXII century male' => [
                Carbon::createFromDate(2150, 2, 2),
                true,
                50,
                '75002020509',
                9
            ],
            'XXII century female' => [
                Carbon::createFromDate(2150, 2, 2),
                false,
                50,
                '85002020509',
                9
            ],
            'Invalid date' => [
                Carbon::createFromDate(2250, 2, 2),
                false,
                50,
                '',
                null,
                'Invalid first year digits'
            ],
        ];
    }
}
