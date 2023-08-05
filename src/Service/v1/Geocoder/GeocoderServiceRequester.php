<?php
declare(strict_types=1);

namespace App\Service\v1\Geocoder;

use App\DTO\CoordinatesDTO;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocoderServiceRequester
{
    private HttpClientInterface $client;

    private string $apiKey;

    public function __construct(
        HttpClientInterface    $geocoderClient,
        string                 $apiKey
    )
    {
        $this->client = $geocoderClient;
        $this->apiKey = $apiKey;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function getCoordinatesByCity(string $city): array
    {
        $content = $this->client->request('GET', '/geo/1.0/direct', [
            'query' => [
                'appid' => $this->apiKey,
                'q' => $city
            ],
        ])->getContent();
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}