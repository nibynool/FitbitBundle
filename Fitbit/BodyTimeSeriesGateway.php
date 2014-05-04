<?php

namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class BodyTimeSeriesGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 * @method array getBmi(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getFat(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getWeight(\DateTime $baseDate, string $period, \DateTime $endDate)
 */
class BodyTimeSeriesGateway extends TimeSeriesEndpointGateway {

    /**
     * base fragment for this resources uri
     * 
     * @var string
     */
    protected static $format = 'body/%s/date';

}
