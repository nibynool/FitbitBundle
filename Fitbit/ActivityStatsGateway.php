<?php
namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class ActivityStatsGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.5.0
 */
class ActivityStatsGateway extends EndpointGateway {

    /**
     * Get user body measurements
     *
     * @access public
     * @version 0.5.0
     *
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getStats()
    {
        return $this->makeApiRequest('user/' . $this->userID . '/activities');
    }
}
