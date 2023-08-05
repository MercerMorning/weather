<?php

namespace App\Tests\Feature\Api\v1;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class WeatherGetTest extends WebTestCase
{
    public function testWithCorrectCity(): void
    {
        static::createClient()->request(
            'GET',
            '/api/v1/weather?city=moscow'
        );
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testWithIncorrectCity(): void
    {
        static::createClient()->request(
            'GET',
            '/api/v1/weather?city=test'
        );
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testWithoutCity(): void
    {
        static::createClient()->request(
            'GET',
            '/api/v1/weather'
        );
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
