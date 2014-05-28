<?php
/**
 *
 * Error Codes: 1501-1502
 */
namespace Nibynool\FitbitInterfaceBundle\Fitbit;

use Nibynool\FitbitInterfaceBundle\Fitbit\Exception as FBException;

/**
 * Class TrackerGateway
 *
 * @package Nibynool\FitbitInterfaceBundle\Fitbit
 *
 * @since 0.5.0
 */
class TrackerGateway extends EndpointGateway {

	/**
	 * Get list of devices and their properties
	 *
	 * @access public
	 * @version 0.5.1
	 * @since 0.5.1
	 *
	 * @throws FBException
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getDevices()
	{
		try
		{
			return $this->makeApiRequest('user/-/devices');
		}
		catch (\Exception $e)
		{
			throw new FBException('Could not get the device list.', 1502, $e);
		}
	}

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
