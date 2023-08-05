<?php

namespace App\Tests\Unit\Service\v1\Weather;

use App\DTO\WeatherDTO;
use App\Service\v1\Weather\WeatherFormatter;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WeatherFormatterTest extends TestCase
{
    public function testFormatWithCorrectData(): void
    {
        $data = [
            'list' => [
                [
                    'dt' => 1661871600,
                    'main' => [
                        "temp" => 296.76,
                        "feels_like" => 296.98,
                        "temp_min" => 296.76,
                        "temp_max" => 297.87,
                        "pressure" => 1015,
                        "sea_level" => 1015,
                        "grnd_level" => 933,
                        "humidity" => 69,
                        "temp_kf" => -1.11
                    ],
                    'clouds' => [
                        'all' => 100
                    ],
                ]
            ],
        ];
        $expectedWeatherDays = [new WeatherDTO(1661871600, 296.76, 100)];
        $formatter = new WeatherFormatter();
        $this->assertEquals($expectedWeatherDays, $formatter->format($data));
    }

    /**
     * @return void
     * @dataProvider incorrectData
     */
    public function testFormatWithIncorrectCorrectData(array $data): void
    {
        $formatter = new WeatherFormatter();
        $this->expectException(InvalidArgumentException::class);
        $formatter->format($data);
    }

    private function incorrectData(): array
    {
        return [
            [
                [
                    'list' => [
                        'main' => [
                            "feels_like" => 296.98,
                            "temp_min" => 296.76,
                            "temp_max" => 297.87,
                            "pressure" => 1015,
                            "sea_level" => 1015,
                            "grnd_level" => 933,
                            "humidity" => 69,
                            "temp_kf" => -1.11
                        ],
                    ],
                ],
                [
                    'main' => [
                        "feels_like" => 296.98,
                        "temp_min" => 296.76,
                        "temp_max" => 297.87,
                        "pressure" => 1015,
                        "sea_level" => 1015,
                        "grnd_level" => 933,
                        "humidity" => 69,
                        "temp_kf" => -1.11
                    ],
                ],
            ]
        ];
    }
}
