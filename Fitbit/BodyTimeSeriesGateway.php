<?php

namespace NibyNool\FitBitBundle\FitBit;

class BodyTimeSeriesGateway extends TimeSeriesEndpointGateway {

    /**
     * base fragment for this resources uri
     * 
     * @var string
     */
    protected static $format = 'body/%s/date';

}
