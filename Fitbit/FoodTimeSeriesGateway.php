<?php

namespace NibyNool\FitBitBundle\FitBit;

class FoodTimeSeriesGateway extends TimeSeriesEndpointGateway {
    /**
     * base fragment for this resources uri
     * 
     * @var string
     */
    protected static $format = 'foods/log/%s/date';

}
