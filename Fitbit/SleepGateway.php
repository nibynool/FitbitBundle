<?php
/**
 *
 * Error Codes: 1101-1103
 */
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
     * @version 0.5.0
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
		    return $this->makeApiRequest('user/' . $this->userID . '/sleep/date/' . $dateStr);
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Unable to get sleep records.', 1101, $e);
	    }
    }

    /**
     * Log user sleep
     *
     * @access public
     * @version 0.5.0
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
	        return $this->makeApiRequest('user/-/sleep', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to add sleep load.', 1102, $e);
        }
    }

    /**
     * Delete user sleep record
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $id Activity log id
     * @throws FBException
     * @return bool
     */
    public function deleteSleep($id)
    {
        try
        {
	        return $this->makeApiRequest('user/-/sleep/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to delete sleep record.', 1103, $e);
        }
    }
}
