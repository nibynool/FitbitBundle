<?php

namespace NibyNool\FitBitBundle\FitBit;

class ActivityStatsGateway extends EndpointGateway {

    /**
     * Get user body measurements
     *
     * @access public
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getStats()
    {
        return $this->makeApiRequest('user/' . $this->userID . '/activities');
    }
}
