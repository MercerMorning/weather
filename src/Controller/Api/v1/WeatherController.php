<?php

namespace App\Controller\Api\v1;

use App\Service\v1\WeatherDataHandlerService;
use App\Service\v1\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    private readonly WeatherDataHandlerService $weatherDataHandlerService;

    /**
     * @param WeatherDataHandlerService $weatherDataHandlerService
     */
    public function __construct(WeatherDataHandlerService $weatherDataHandlerService)
    {
        $this->weatherDataHandlerService = $weatherDataHandlerService;
    }

    #[Route('/api/v1/weather', name: 'app_api_v1_weather')]
    public function index(): Response
    {
        $this->weatherDataHandlerService->getWeatherByCity('Москваава');
        return $this->render('api/v1/weather/index.html.twig', [
            'controller_name' => 'WeatherController',
        ]);
    }
}
