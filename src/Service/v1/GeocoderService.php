<?php
declare(strict_types=1);

namespace App\Service\v1;

use App\DTO\CoordinatesDTO;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocoderService
{
    readonly private HttpClientInterface $client;

    private readonly TagAwareCacheInterface $cache;

    private string $apiKey;

    public function __construct(
        HttpClientInterface $geocoderClient,
        string $apiKey,
        TagAwareCacheInterface $cache
    ) {
        $this->client = $geocoderClient;
        $this->apiKey = $apiKey;
        $this->cache = $cache;
    }

    public function getCoordinatesByCity(string $city) :CoordinatesDTO
    {
        $cityCoordinates = $this->cache->get('city_coordinates_' . $city, function(ItemInterface $item) use ($city) {
            $content = $this->client->request('GET', '', [
                'query' => [
                    'appid' => $this->apiKey,
                    'q' => $city
                ],
            ])->getContent();
            $cityInfo = json_decode($content, true, 512, JSON_THROW_ON_ERROR)[0];
            return [
                'latitude' => $cityInfo['lat'],
                'longitude' => $cityInfo['lon'],
            ];
        });

        return new CoordinatesDTO($cityCoordinates['latitude'], $cityCoordinates['longitude']);
    }
}