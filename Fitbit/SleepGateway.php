<?php
namespace NibyNool\FitBitBundle\FitBit;

use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class SleepGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class SleepGateway extends EndpointGateway {

    /**
     * Get user sleep log entries for specific date
     *
     * @access public
     *
     * @todo Add validation for the date
     *
     * @param  \DateTime $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getSleep($date)
    {
        $dateStr = $date->format('Y-m-d');

	    try
	    {
		    $returnValue = $this->makeApiRequest('user/' . $this->userID . '/sleep/date/' . $dateStr);
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException($e->getMessage());
	    }
	    return $returnValue;
    }

    /**
     * Log user sleep
     *
     * @access public
     *
     * @todo Add validation for the date
     *
     * @param \DateTime $date Sleep date and time (set proper timezone, which could be fetched via getProfile)
     * @param string $duration Duration millis
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function logSleep(\DateTime $date, $duration)
    {
        $parameters = array();
        $parameters['date'] = $date->format('Y-m-d');
        $parameters['startTime'] = $date->format('H:i');
        $parameters['duration'] = $duration;

        try
        {
	        $returnValue = $this->makeApiRequest('user/-/sleep', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }

    /**
     * Delete user sleep record
     *
     * @access public
     *
     * @param string $id Activity log id
     * @throws FBException
     * @return bool
     */
    public function deleteSleep($id)
    {
        try
        {
	        $returnValue = $this->makeApiRequest('user/-/sleep/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException($e->getMessage());
        }
	    return $returnValue;
    }
}
