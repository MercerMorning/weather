<?php

namespace App\Tests\Unit\Service\v1\Weather;

use App\DTO\CoordinatesDTO;
use App\DTO\WeatherDTO;
use App\Service\v1\Weather\WeatherService;
use App\Service\v1\Weather\WeatherServiceRequester;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Response\MockResponse;

class WeatherServiceTest extends KernelTestCase
{
    public function testWithIncorrectCoordinates(): void
    {
        $container = static::getContainer();
        $weatherServiceRequester = $this->createMock(WeatherServiceRequester::class);
        $weatherServiceRequester
            ->method('getWeatherByCoordinates')
            ->willReturn([
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
            ]);
        $container->set(WeatherServiceRequester::class, $weatherServiceRequester);
        /**
         * @var $weatherService WeatherService
         */
        $weatherService = $container->get(WeatherService::class);

        $this
            ->assertEquals(
                [
                    new WeatherDTO(1661871600, 296.76, 100)
                ],
                $weatherService
                    ->getWeatherByCoordinates(new CoordinatesDTO(51.5073219, -0.1276474))
            );
    }

    public function testWithIncorrectCity(): void
    {
        $container = static::getContainer();
        $weatherServiceRequester = $this->createMock(WeatherServiceRequester::class);
        $weatherServiceRequester
            ->method('getWeatherByCoordinates')
            ->willThrowException(new ClientException(new MockResponse('error', [
                'http_code' => 500
            ])));
        $container->set(WeatherServiceRequester::class, $weatherServiceRequester);
        /**
         * @var $weatherService WeatherService
         */
        $weatherService = $container->get(WeatherService::class);
        $this->expectException(ClientException::class);
        $weatherService
            ->getWeatherByCoordinates(new CoordinatesDTO(51.5073219, -0.1276474));
    }
}
