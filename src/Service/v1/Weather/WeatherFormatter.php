<?php
declare(strict_types=1);

namespace App\Service\v1\Weather;

use App\DTO\WeatherDTO;
use InvalidArgumentException;

class WeatherFormatter
{
    public function format(array $data) :array
    {
        if (!isset($data['list'])) {
            throw new InvalidArgumentException('Data does not have list');
        }
        return array_map(static function ($day) {
            if (!isset($day['dt'], $day['main']['temp'], $day['clouds']['all'])) {
                throw new InvalidArgumentException('Data does not have dt, temp, clouds');
            }
            return new WeatherDTO($day['dt'], $day['main']['temp'], $day['clouds']['all']);
        }, $data['list']);
    }
}