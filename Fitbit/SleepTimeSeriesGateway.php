<?php

namespace NibyNool\FitBitBundle\FitBit;

class SleepTimeSeriesGateway extends TimeSeriesEndpointGateway {


    /**
     * base fragment for this resources uri
     * 
     * @var sting
     */
    protected static $format = 'sleep/%s/date';

}
