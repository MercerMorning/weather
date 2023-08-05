<?php
declare(strict_types=1);

namespace App\DTO;

use DateTime;

class WeatherDTO
{
    private DateTime $date;
    private float $temperature;
    private float $clouds;

    /**
     * @param DateTime $date
     * @param float $temperature
     * @param float $clouds
     */
    public function __construct($unixDateTime, float $temperature, float $clouds)
    {
        $dateTime = new DateTime();
        $dateTime->setTimestamp($unixDateTime);
        $this->date = $dateTime;
        $this->temperature = $temperature;
        $this->clouds = $clouds;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return float
     */
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    /**
     * @return float
     */
    public function getClouds(): float
    {
        return $this->clouds;
    }


}