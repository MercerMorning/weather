<?php
declare(strict_types=1);

namespace App\Service\v1\Weather;

use App\DTO\CoordinatesDTO;
use DateTime;
use DateTimeInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class WeatherService
{
    private WeatherServiceRequester $weatherServiceRequester;
    private WeatherFormatter $weatherFormatter;
    private CacheItemPoolInterface $cacheItemPool;

    /**
     * @param WeatherServiceRequester $weatherServiceRequester
     * @param WeatherFormatter $weatherFormatter
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(WeatherServiceRequester $weatherServiceRequester, WeatherFormatter $weatherFormatter, CacheItemPoolInterface $cacheItemPool)
    {
        $this->weatherServiceRequester = $weatherServiceRequester;
        $this->weatherFormatter = $weatherFormatter;
        $this->cacheItemPool = $cacheItemPool;
    }


    /**
     * @throws \JsonException
     * @throws InvalidArgumentException
     */
    public function getWeatherByCoordinates(CoordinatesDTO $coordinates) :array
    {
        $cachedData = $this->cacheItemPool->getItem(
            'weather__lat' . $coordinates->getLatitude() .
            '_lon' . $coordinates->getLongitude()
        );

        if (!$cachedData->isHit()) {
            $cachedData->expiresAt($this->getCacheExpiredAt());
            $weatherData = $this->weatherServiceRequester->getWeatherByCoordinates($coordinates);
            $formattedWeatherData = $this->weatherFormatter->format($weatherData);
            $cachedData->set($formattedWeatherData);
        }

        return $cachedData->get();
    }

    private function getCacheExpiredAt() :DateTimeInterface
    {
        $expiresAtDateTime = new DateTime();
        $expiresAtDateTime->modify('+1 hour');
        while (
            $expiresAtDateTime->format('H') % 3 !== 0
            && $expiresAtDateTime->format('H') !== '00'
        ) {
            $expiresAtDateTime->modify('+1 hour');
        }
        return $expiresAtDateTime;
    }
}