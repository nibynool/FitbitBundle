<?php

namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class BodyTimeSeriesGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 * @method array getWeight(string $baseDate, string $period, string $endDate)
 */
class BodyTimeSeriesGateway extends TimeSeriesEndpointGateway {

    /**
     * base fragment for this resources uri
     * 
     * @var string
     */
    protected static $format = 'body/log/%s/date';

}
