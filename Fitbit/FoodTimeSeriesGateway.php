<?php
/**
 *
 * Error Codes: 18XX
 */
namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class FoodTimeSeriesGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 * @version 0.5.0
 *
 * @method array getCaloriesIn(\DateTime $baseDate, \DateTime $endDate)
 * @method array getWater(\DateTime $baseDate, \DateTime $endDate)
 */
class FoodTimeSeriesGateway extends TimeSeriesEndpointGateway {
    /**
     * base fragment for this resources uri
     * 
     * @var string
     */
    protected static $format = 'foods/log/%s/date';
}
