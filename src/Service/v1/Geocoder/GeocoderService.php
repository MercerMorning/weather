<?php
declare(strict_types=1);

namespace App\Service\v1\Geocoder;

use App\DTO\CoordinatesDTO;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class GeocoderService
{
    private GeocoderServiceRequester $geocoderServiceRequester;
    private GeocoderFormatter $geocoderFormatter;

    private CacheItemPoolInterface $cacheItemPool;

    /**
     * @param GeocoderServiceRequester $geocoderServiceRequester
     * @param GeocoderFormatter $geocoderFormatter
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(GeocoderServiceRequester $geocoderServiceRequester, GeocoderFormatter $geocoderFormatter, CacheItemPoolInterface $cacheItemPool)
    {
        $this->geocoderServiceRequester = $geocoderServiceRequester;
        $this->geocoderFormatter = $geocoderFormatter;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * @throws InvalidArgumentException
     * @throws \JsonException
     */
    public function getCoordinatesByCity(string $city): CoordinatesDTO
    {
        $cachedData = $this->cacheItemPool->getItem(
            'city__' . $city
        );

        if (!$cachedData->isHit()) {
            $cachedData->expiresAfter(365 * 24 * 60 * 60);
            $coordinateData = $this->geocoderServiceRequester->getCoordinatesByCity($city);
            $formattedCoordinatesData = $this->geocoderFormatter->format($coordinateData);
            $cachedData->set($formattedCoordinatesData);
        }

        return $cachedData->get();
    }

}