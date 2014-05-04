<?php

namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class ActivityTimeSeriesGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @method object getCalories(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getCaloriesBMR(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getSteps(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getDistance(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getFloors(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getElevation(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getMinutesSedentary(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getMinutesLightlyActive(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getMinutesFairlyActive(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getMinutesVeryActive(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 * @method object getActivityCalories(bool $tracker = false, \DateTime $baseDate = null, string $period = null, \DateTime $endDate = null)
 */
class ActivityTimeSeriesGateway extends TimeSeriesEndpointGateway {

    /**
     * base fragment for this resources uri
     * 
     * @var string
     */
    protected static $format = 'activities/%s/date';

    /**
     * convert to tracker only fragment
     * 
     * @param string $fragment
     * @return string
     */
    protected function trackerOnlyFragment($fragment)
    {   
        return str_replace('activities', 'activities/tracker', $fragment);
    }

    /**
     * extended get to all for tracker only resource calls
     *
     * @throws Exception
     * @param  string $fragment
     * @param  bool $tracker
     * @param  \DateTime|string $baseDate
     * @param  string $period
     * @param  \DateTime|string $endDate
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function get($fragment, $tracker = false, $baseDate = null, $period = null, $endDate = null)
    {
        $fragment = ($tracker) ? $this->trackerOnlyFragment($fragment) : $fragment;
        return parent::get($fragment, $baseDate, $period, $endDate);
    }

    /**
     * extended call, to ensure methods without tracker
     * have tracker set to false
     * 
     * {@inheritdoc}
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, array('getCaloriesBMR'))) $parameters[0] = false; 
        return parent::__call($method, $parameters);        
    }
}