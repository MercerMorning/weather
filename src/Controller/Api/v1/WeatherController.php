<?php

namespace App\Controller\Api\v1;

use App\Controller\Common\FormErrorsAdapterTrait;
use App\DTO\Request\WeatherDTO;
use App\Factory\JsonResponseFactory;
use App\Factory\v1\WeatherApiResponseFactory;
use App\Form\WeatherType;
use App\Service\v1\WeatherDataHandlerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    use FormErrorsAdapterTrait;
    private WeatherDataHandlerService $weatherDataHandlerService;
    private WeatherApiResponseFactory $weatherApiResponseFactory;
    private FormFactoryInterface $formFactory;
    private JsonResponseFactory $jsonResponseFactory;

    /**
     * @param WeatherDataHandlerService $weatherDataHandlerService
     * @param WeatherApiResponseFactory $weatherApiResponseFactory
     * @param FormFactoryInterface $formFactory
     * @param JsonResponseFactory $jsonResponseFactory
     */
    public function __construct(WeatherDataHandlerService $weatherDataHandlerService, WeatherApiResponseFactory $weatherApiResponseFactory, FormFactoryInterface $formFactory, JsonResponseFactory $jsonResponseFactory)
    {
        $this->weatherDataHandlerService = $weatherDataHandlerService;
        $this->weatherApiResponseFactory = $weatherApiResponseFactory;
        $this->formFactory = $formFactory;
        $this->jsonResponseFactory = $jsonResponseFactory;
    }


    /**
     * @throws \JsonException
     */
    #[Route('/api/v1/weather', name: 'app_api_v1_weather', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $form = $this->formFactory->create(WeatherType::class, new WeatherDTO());
        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->jsonResponseFactory->fail($this->getErrorMessages($form));
        }
        $weatherDays = $this->weatherDataHandlerService->getWeatherByCity(
            $request->query->get('city')
        );
        return $this->weatherApiResponseFactory->weatherFor5Days($weatherDays);
    }
}
