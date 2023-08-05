<?php

namespace App\Tests\Unit\Service\v1\Geocoder;

use App\DTO\CoordinatesDTO;
use App\Service\v1\Geocoder\GeocoderFormatter;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GeocoderFormatterTest extends TestCase
{
    public function testFormatWithCorrectData(): void
    {
        $data = [
            [
                'name' => 'Moscow',
                'local_names' => [],
                'lat' => 51.5073219,
                'lon' => -0.1276474,
                'country' => 'RU',
            ]
        ];
        $expectedCoordinates = new CoordinatesDTO($data[0]['lat'], $data[0]['lon']);
        $formatter = new GeocoderFormatter();
        $this->assertEquals($expectedCoordinates, $formatter->format($data));
    }

    public function testFormatWithIncorrectCorrectData(): void
    {
        $data = [
            [
                'name' => 'Moscow',
                'local_names' => [],
                'country' => 'RU',
            ]
        ];
        $formatter = new GeocoderFormatter();
        $this->expectException(InvalidArgumentException::class);
        $formatter->format($data);
    }
}
