<?php
/**
 *
 * Error Codes: 1501
 */
namespace NibyNool\FitBitBundle\FitBit;

use NibyNool\FitBitBundle\FitBit\Exception as FBException;

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
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getAlarms($tracker)
    {
        try
        {
	        return $this->makeApiRequest('user/' . $this->userID . '/devices/tracker/' . $tracker . '/alarms');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not get silent alarms.', 1501, $e);
        }
    }
}
