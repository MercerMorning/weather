<?php
declare(strict_types=1);

namespace App\Factory\v1;
use App\DTO\WeatherDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WeatherApiResponseFactory
{
    public function weatherFor5Days(array $weatherDays) :JsonResponse
    {
        $result = [];
        /**
         * @var $weatherDay WeatherDTO
         */
        foreach ($weatherDays as $weatherDay) {
            $result[] = [
                'date' => $weatherDay->getDate()->format('d.m.Y H:i'),
                'temperature' => $weatherDay->getTemperature(),
                'clouds' => (int) $weatherDay->getClouds(),
            ];
        }
        return new JsonResponse($result, Response::HTTP_OK);
    }
}