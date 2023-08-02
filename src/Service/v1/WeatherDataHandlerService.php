<?php
declare(strict_types=1);

namespace App\Service\v1;

class WeatherDataHandlerService
{
    private readonly GeocoderService $geocoderService;
    private readonly WeatherService $weatherService;

    /**
     * @param GeocoderService $geocoderService
     * @param WeatherService $weatherService
     */
    public function __construct(GeocoderService $geocoderService, WeatherService $weatherService)
    {
        $this->geocoderService = $geocoderService;
        $this->weatherService = $weatherService;
    }


    public function getWeatherByCity(string $city)
    {
        $coordinates = $this->geocoderService->getCoordinatesByCity('moscow');
        $weather = $this->weatherService->getWeatherByCoordinates($coordinates);
    }
}