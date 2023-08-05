<?php

namespace App\Tests\Unit\Service\v1\Geocoder;

use App\DTO\CoordinatesDTO;
use App\Service\v1\Geocoder\GeocoderService;
use App\Service\v1\Geocoder\GeocoderServiceRequester;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Response\MockResponse;
class GeocoderServiceTest extends KernelTestCase
{
    public function testWithCorrectCity(): void
    {
        $container = static::getContainer();
        $geocoderServiceRequester = $this->createMock(GeocoderServiceRequester::class);
        $geocoderServiceRequester
            ->method('getCoordinatesByCity')
            ->willReturn([
                [
                    'name' => 'Moscow',
                    'local_names' => [],
                    'lat' => 51.5073219,
                    'lon' => -0.1276474,
                    'country' => 'RU',
                ]
            ]);
        $container->set(GeocoderServiceRequester::class, $geocoderServiceRequester);
        /**
         * @var $geocoderService GeocoderService
         */
        $geocoderService = $container->get(GeocoderService::class);
        $this
            ->assertEquals(
                new CoordinatesDTO(51.5073219, -0.1276474),
                $geocoderService
                    ->getCoordinatesByCity('Moscow')
            );
    }

    public function testWithIncorrectCity(): void
    {
        $container = static::getContainer();
        $geocoderServiceRequester = $this->createMock(GeocoderServiceRequester::class);
        $geocoderServiceRequester
            ->method('getCoordinatesByCity')
            ->willThrowException(new ClientException(new MockResponse('error', [
                'http_code' => 500
            ])));
        $container->set(GeocoderServiceRequester::class, $geocoderServiceRequester);
        /**
         * @var $geocoderService GeocoderService
         */
        $geocoderService = $container->get(GeocoderService::class);
        $this->expectException(ClientException::class);
        $geocoderService
            ->getCoordinatesByCity('test');
    }
}
