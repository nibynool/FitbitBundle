<?php
namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class TrackerGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.5.0
 */
class TrackerGateway extends EndpointGateway {

    /**
     * Get alarm settings
     *
     * @access public
     * @version 0.5.0
     *
     * @param  string $tracker
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getAlarms($tracker)
    {
        return $this->makeApiRequest('user/' . $this->userID . '/devices/tracker/' . $tracker . '/alarms');
    }
}
