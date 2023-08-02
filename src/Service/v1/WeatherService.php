<?php
declare(strict_types=1);

namespace App\Service\v1;

use App\DTO\CoordinatesDTO;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    readonly private HttpClientInterface $client;

    private readonly TagAwareCacheInterface $cache;

    private string $apiKey;

    public function __construct(
        HttpClientInterface    $weatherClient,
        string                 $apiKey,
        TagAwareCacheInterface $cache
    )
    {
        $this->client = $weatherClient;
        $this->apiKey = $apiKey;
        $this->cache = $cache;
    }

    public function getWeatherByCoordinates(CoordinatesDTO $coordinates)
    {
        $content = $this->client->request('GET', '', [
            'query' => [
                'appid' => $this->apiKey,
                'lat' => $coordinates->getLatitude(),
                'lon' => $coordinates->getLongitude(),
            ],
        ])->getContent();
    }
}