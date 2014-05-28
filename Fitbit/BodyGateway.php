<?php
/**
 *
 * Error Codes: 301 - 311
 */
namespace Nibynool\FitbitInterfaceBundle\Fitbit;

use Nibynool\FitbitInterfaceBundle\Fitbit\Exception as FBException;

/**
 * Class BodyGateway
 *
 * @package Nibynool\FitbitInterfaceBundle\Fitbit
 *
 * @since 0.1.0
 *
 * @todo Is there a function to delete a body log?
 * @todo Is there a function to delete a weight log?
 * @todo Is there a function to delete a glucose log?
 */
class BodyGateway extends EndpointGateway
{
    /**
     * Get user body measurements
     *
     * @access public
     * @version 0.5.0
     *
     * @param  \DateTime $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getBody(\DateTime $date)
    {
        $dateStr = $date->format('Y-m-d');

        try
        {
	        return $this->makeApiRequest('user/' . $this->userID . '/body/date/' . $dateStr);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Body data request failed.', 301, $e);
        }
    }

    /**
     * Log user body measurements
     *
     * @access public
     * @version 0.5.0
     *
     * @param \DateTime $date Date Log entry date (set proper timezone, which could be fetched via getProfile)
     * @param string $weight Float number. For en_GB units, provide floating number of stones (i.e. 11 st. 4 lbs = 11.2857143)
     * @param string $fat Float number
     * @param string $bicep Float number
     * @param string $calf Float number
     * @param string $chest Float number
     * @param string $forearm Float number
     * @param string $hips Float number
     * @param string $neck Float number
     * @param string $thigh Float number
     * @param string $waist Float number
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function logBody(\DateTime $date, $weight = null, $fat = null, $bicep = null, $calf = null, $chest = null, $forearm = null, $hips = null, $neck = null, $thigh = null, $waist = null)
    {
        $parameters = array();
        $parameters['date'] = $date->format('Y-m-d');

        if (isset($weight))  $parameters['weight'] = $weight;
        if (isset($fat))     $parameters['fat'] = $fat;
        if (isset($bicep))   $parameters['bicep'] = $bicep;
        if (isset($calf))    $parameters['calf'] = $calf;
        if (isset($chest))   $parameters['chest'] = $chest;
        if (isset($forearm)) $parameters['forearm'] = $forearm;
        if (isset($hips))    $parameters['hips'] = $hips;
        if (isset($neck))    $parameters['neck'] = $neck;
        if (isset($thigh))   $parameters['thigh'] = $thigh;
        if (isset($waist))   $parameters['waist'] = $waist;

        try
        {
	        return $this->makeApiRequest('user/-/body', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Body data log submission failed.', 302, $e);
        }
    }

    /**
     * Log user weight
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Can the date cope with a time?
     * @todo Can we allow different weight units?
     *
     * @param string $weight Float number. For en_GB units, provide floating number of stones (i.e. 11 st. 4 lbs = 11.2857143)
     * @param \DateTime $date If present, log entry date, now by default (set proper timezone, which could be fetched via getProfile)
     * @throws FBException
	 * @return bool
     */
    public function logWeight($weight, \DateTime $date = null)
    {
        $parameters = array();
        $parameters['weight'] = $weight;
        if ($date) $parameters['date'] = $date->format('Y-m-d');

        try
        {
	        return $this->makeApiRequest('user/-/body/weight', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Weight data log submission failed.', 303, $e);
        }
    }

    /**
     * Get user blood pressure log entries for specific date
     *
     * @access public
     * @version 0.5.0
     *
     * @param  \DateTime $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getBloodPressure(\DateTime $date)
    {
        $dateStr = $date->format('Y-m-d');

	    try
	    {
            return $this->makeApiRequest('user/-/bp/date/' . $dateStr);
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Blood pressure request failed.', 304, $e);
	    }
    }

    /**
     * Log user blood pressure
     *
     * @access public
     * @version 0.5.0
     *
     * @param \DateTime $date Log entry date and time (set proper timezone, which could be fetched via getProfile)
     * @param string $systolic Systolic measurement
     * @param string $diastolic Diastolic measurement
     * @param bool $time If true, use the time from $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function logBloodPressure(\DateTime $date, $systolic, $diastolic, $time = false)
    {
        $parameters = array();
        $parameters['date'] = $date->format('Y-m-d');
        $parameters['systolic'] = $systolic;
        $parameters['diastolic'] = $diastolic;
        if ($time) $parameters['time'] = $date->format('H:i');

        try
        {
	        return $this->makeApiRequest('user/-/bp', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Blood pressure submission failed.', 305, $e);
        }
    }

    /**
     * Delete user blood pressure record
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $id Blood pressure log id
     * @throws FBException
     * @return bool
     */
    public function deleteBloodPressure($id)
    {
        try
        {
	        return $this->makeApiRequest('user/-/bp/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Blood pressure record deletion failed.', 306, $e);
        }
    }

    /**
     * Get user glucose log entries for specific date
     *
     * @access public
     * @version 0.5.0
     *
     * @param  \DateTime $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getGlucose(\DateTime $date)
    {
        $dateStr = $date->format('Y-m-d');

        try
        {
	        return $this->makeApiRequest('user/-/glucose/date/' . $dateStr);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Glucose request failed.', 307, $e);
        }
    }

    /**
     * Log user glucose and HbA1c
     *
     * @access public
     * @version 0.5.0
     *
     * @param \DateTime $date Log entry date and time (set proper timezone, which could be fetched via getProfile)
     * @param string $tracker Name of the glucose tracker
     * @param string $glucose Glucose measurement
     * @param string $hba1c Glucose measurement
     * @param bool $time If true, use the time from $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function logGlucose(\DateTime $date, $tracker, $glucose, $hba1c = null, $time = false)
    {
        $parameters = array();
        $parameters['date'] = $date->format('Y-m-d');
        $parameters['tracker'] = $tracker;
        $parameters['glucose'] = $glucose;
        if ($hba1c) $parameters['hba1c'] = $hba1c;
        if ($time)  $parameters['time'] = $date->format('H:i');

        try
        {
	        return $this->makeApiRequest('user/-/glucose', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Glucose log submission failed.', 308, $e);
        }
    }

    /**
     * Get user heart rate log entries for specific date
     *
     * @access public
     * @version 0.5.0
     *
     * @param  \DateTime $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getHeartRate(\DateTime $date)
    {
        $dateStr = $date->format('Y-m-d');

        try
        {
	        return $this->makeApiRequest('user/-/heart/date/' . $dateStr);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Heart rate request failed.', 309, $e);
        }
    }

    /**
     * Log user heart rate
     *
     * @access public
     * @version 0.5.0
     *
     * @param \DateTime $date Log entry date and time (set proper timezone, which could be fetched via getProfile)
     * @param string $tracker Name of the glucose tracker
     * @param string $heartRate Heart rate measurement
     * @param bool $time If true, use the time from $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function logHeartRate(\DateTime $date, $tracker, $heartRate, $time = false)
    {
        $parameters = array();
        $parameters['date'] = $date->format('Y-m-d');
        $parameters['tracker'] = $tracker;
        $parameters['heartRate'] = $heartRate;
        if ($time) $parameters['time'] = $date->format('H:i');

        try
        {
	        return $this->makeApiRequest('user/-/heart', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Heart rate log submission failed.', 310, $e);
        }
    }

    /**
     * Delete user heart rate record
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $id Heart rate log id
     * @throws FBException
     * @return bool
     */
    public function deleteHeartRate($id)
    {
        try
        {
	        return $this->makeApiRequest('user/-/heart/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Heart rate record deletion failed.', 311, $e);
        }
    }
}
