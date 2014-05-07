<?php
namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class WaterGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class WaterGateway extends EndpointGateway {

    /**
     * Get user water log entries for specific date
     *
     * @access public
     *
     * @todo Remove the $dateStr variable
     * @todo Add validation for the date
     * @todo Handle failed API requests gracefully
     *
     * @param  \DateTime $date
     * @param  String $dateStr
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getWater(\DateTime $date, $dateStr = null)
    {
        if (!isset($dateStr)) $dateStr = $date->format('Y-m-d');

        return $this->makeApiRequest('user/-/foods/log/water/date/' . $dateStr);
    }

    /**
     * Log user water
     *
     * @access public
     *
     * @todo Add validation for the date
     * @todo Can this use a time in the date string?
     * @todo Handle failed API requests gracefully
     *
     * @param \DateTime $date Log entry date (set proper timezone, which could be fetched via getProfile)
     * @param string $amount Amount in ml/fl oz (as set with setMetric) or waterUnit
     * @param string $waterUnit Water Unit ("ml", "fl oz" or "cup")
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function logWater(\DateTime $date, $amount, $waterUnit = null)
    {
        $waterUnits = array('ml', 'fl oz', 'cup');

        $parameters = array();
        $parameters['date'] = $date->format('Y-m-d');
        $parameters['amount'] = $amount;
        if (isset($waterUnit) && in_array($waterUnit, $waterUnits)) $parameters['unit'] = $waterUnit;

        return $this->makeApiRequest('user/-/foods/log/water', 'POST', $parameters);
    }

    /**
     * Delete user water record
     *
     * @access public
     *
     * @todo Handle failed API requests gracefully
     *
     * @param string $id Water log id
     * @return bool
     */
    public function deleteWater($id)
    {
        return $this->makeApiRequest('user/-/foods/log/water/' . $id, 'DELETE');
    }
}
