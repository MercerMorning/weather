<?php
declare(strict_types=1);

namespace App\Service\v1\Weather;

use App\DTO\CoordinatesDTO;
use App\DTO\WeatherDTO;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherServiceRequester
{
    private HttpClientInterface $client;

    private string $apiKey;

    public function __construct(
        HttpClientInterface    $weatherClient,
        string                 $apiKey
    )
    {
        $this->client = $weatherClient;
        $this->apiKey = $apiKey;
    }

    /**
     * @return WeatherDTO[]
     */
    public function getWeatherByCoordinates(CoordinatesDTO $coordinates): array
    {
        $content = $this->client->request('GET', '/data/2.5/forecast', [
            'query' => [
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lat' => $coordinates->getLatitude(),
                'lon' => $coordinates->getLongitude(),
            ],
        ])->getContent();

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}