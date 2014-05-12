<?php
/**
 *
 * Error Codes: 701
 */
namespace NibyNool\FitBitBundle\FitBit;

use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class ActivityStatsGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.5.0
 */
class ActivityStatsGateway extends EndpointGateway
{
    /**
     * Get user body measurements
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getStats()
    {
        try
        {
	        return $this->makeApiRequest('user/' . $this->userID . '/activities');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to get activity statistics.', 701, $e);
        }
    }
}
