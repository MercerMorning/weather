<?php
declare(strict_types=1);

namespace App\Service\v1\Geocoder;

use App\DTO\CoordinatesDTO;
use InvalidArgumentException;

class GeocoderFormatter
{
    public function format(array $data): CoordinatesDTO
    {
        if (!isset($data[0]['lat'], $data[0]['lon'])) {
            throw new InvalidArgumentException('Data does not have latitude, longitude');
        }
        return new CoordinatesDTO($data[0]['lat'], $data[0]['lon']);
    }
}