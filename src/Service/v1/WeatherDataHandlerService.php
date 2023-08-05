<?php
declare(strict_types=1);

namespace App\Service\v1;

use App\Service\v1\Geocoder\GeocoderService;
use App\Service\v1\Weather\WeatherService;
use Psr\Cache\InvalidArgumentException;

class WeatherDataHandlerService
{
    private GeocoderService $geocoderService;
    private WeatherService $weatherService;

    /**
     * @param GeocoderService $geocoderService
     * @param WeatherService $weatherService
     */
    public function __construct(GeocoderService $geocoderService, WeatherService $weatherService)
    {
        $this->geocoderService = $geocoderService;
        $this->weatherService = $weatherService;
    }


    /**
     * @throws \JsonException
     * @throws InvalidArgumentException
     */
    public function getWeatherByCity(string $city) :array
    {
        $coordinates = $this->geocoderService->getCoordinatesByCity($city);
        return $this->weatherService->getWeatherByCoordinates($coordinates);
    }
}