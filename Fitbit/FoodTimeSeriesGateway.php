<?php

namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class FoodTimeSeriesGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 * @method array getCaloriesIn(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getWater(\DateTime $baseDate, string $period, \DateTime $endDate)
 */
class FoodTimeSeriesGateway extends TimeSeriesEndpointGateway {
    /**
     * base fragment for this resources uri
     * 
     * @var string
     */
    protected static $format = 'foods/log/%s/date';

}
