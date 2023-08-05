<?php

namespace App\Tests\Unit\Service\v1;

use App\DTO\CoordinatesDTO;
use App\DTO\WeatherDTO;
use App\Service\v1\Geocoder\GeocoderService;
use App\Service\v1\Weather\WeatherService;
use App\Service\v1\WeatherDataHandlerService;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Response\MockResponse;

class WeatherDataHandlerServiceTest extends KernelTestCase
{
    public function testWithCorrectCity(): void
    {
        $container = static::getContainer();
        $geocoderService = $this->createMock(GeocoderService::class);
        $geocoderService->method('getCoordinatesByCity')->willReturn(
            new CoordinatesDTO(51.5073219, -0.1276474)
        );
        $weatherService = $this->createMock(WeatherService::class);
        $weatherService->method('getWeatherByCoordinates')->willReturn(
            [new WeatherDTO(1661871600, 296.76, 100)]
        );
        $container->set(GeocoderService::class, $geocoderService);
        $container->set(WeatherService::class, $weatherService);
        /**
         * @var $weatherDataHandlerService WeatherDataHandlerService
         */
        $weatherDataHandlerService = $container->get(WeatherDataHandlerService::class);

        $this
            ->assertEquals(
                [
                    new WeatherDTO(1661871600, 296.76, 100)
                ],
                $weatherDataHandlerService
                    ->getWeatherByCity('Moscow')
            );
    }

    public function testWithIncorrectCity(): void
    {
        $container = static::getContainer();
        $geocoderService = $this->createMock(GeocoderService::class);
        $geocoderService->method('getCoordinatesByCity')
            ->willThrowException(new ClientException(new MockResponse('error', [
                'http_code' => 500
            ])));
        $weatherService = $this->createMock(WeatherService::class);
        $weatherService->expects(new InvokedCount(0))
            ->method('getWeatherByCoordinates');
        $container->set(GeocoderService::class, $geocoderService);
        $container->set(WeatherService::class, $weatherService);
        /**
         * @var $weatherDataHandlerService WeatherDataHandlerService
         */
        $weatherDataHandlerService = $container->get(WeatherDataHandlerService::class);
        $this->expectException(ClientException::class);
        $weatherDataHandlerService
            ->getWeatherByCity('Moscow');
    }
}
