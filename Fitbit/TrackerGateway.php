<?php

namespace NibyNool\FitBitBundle\FitBit;

class TrackerGateway extends EndpointGateway {

    /**
     * Get alarm settings
     *
     * @access public
     * @param  string $tracker
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getAlarms($tracker)
    {
        return $this->makeApiRequest('user/' . $this->userID . '/devices/tracker/' . $tracker . '/alarms');
    }
}
