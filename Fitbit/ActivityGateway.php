<?php
/**
 *
 * Error Codes: 601 - 611
 */
namespace NibyNool\FitBitBundle\FitBit;

use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class ActivityGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class ActivityGateway extends EndpointGateway
{
    /**
     * Get user's activity statistics
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getActivityStats()
    {
        try
        {
	        return $this->makeApiRequest('user/' . $this->userID . '/activities');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Activity statistics request failed.', 601, $e);
        }
    }

    /**
     * Get user activities for specific date
     *
     * @access public
     * @version 0.5.0
     *
     * @param  \DateTime $date
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getActivities(\DateTime $date)
    {
        $dateStr = $date->format('Y-m-d');

        try
        {
	        return $this->makeApiRequest('user/' . $this->userID . '/activities/date/' . $dateStr);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Get activities by date request failed.', 602, $e);
        }
    }

    /**
     * Get user recent activities
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getRecentActivities()
    {
	    try
	    {
		    return $this->makeApiRequest('user/-/activities/recent');
	    }
	    catch (Exception $e)
	    {
		    throw new FBException('Get recent activities request failed.', 603, $e);
	    }
    }

    /**
     * Get user frequent activities
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFrequentActivities()
    {
        try
        {
	        return $this->makeApiRequest('user/-/activities/frequent');
        }
        catch (Exception $e)
        {
	        throw new FBException('Request for frequent activities failed.', 604, $e);
        }
    }

    /**
     * Get user favorite activities
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFavoriteActivities()
    {
        try
        {
	        return $this->makeApiRequest('user/-/activities/favorite');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Request for favorite activities failed.', 605, $e);
        }
    }

    /**
     * Log user activity
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Move distance units to a configuration file
     * @todo Validate parameters where possible
     *
     * @param \DateTime $date Activity date and time (set proper timezone, which could be fetched via getProfile)
     * @param string $activityId Activity Id (or Intensity Level Id) from activities database,
     *                                  see http://wiki.fitbit.com/display/API/API-Log-Activity
     * @param string $duration Duration millis
     * @param string $calories Manual calories to override FitBit estimate
     * @param string $distance Distance in km/miles (as set with setMetric)
     * @param string $distanceUnit Distance unit string (see http://wiki.fitbit.com/display/API/API-Distance-Unit)
     * @param string $activityName The name of the activity
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function logActivity(\DateTime $date, $activityId, $duration, $calories = null, $distance = null, $distanceUnit = null, $activityName = null)
    {
        $distanceUnits = array('Centimeter', 'Foot', 'Inch', 'Kilometer', 'Meter', 'Mile', 'Millimeter', 'Steps', 'Yards');

        $parameters = array();
        $parameters['date'] = $date->format('Y-m-d');
        $parameters['startTime'] = $date->format('H:i');
        if (isset($activityName)) {
            $parameters['activityName'] = $activityName;
            $parameters['manualCalories'] = $calories;
        } else {
            $parameters['activityId'] = $activityId;
            if (isset($calories))
                $parameters['manualCalories'] = $calories;
        }
        $parameters['durationMillis'] = $duration;
        if (isset($distance))
            $parameters['distance'] = $distance;
        if (isset($distanceUnit) && in_array($distanceUnit, $distanceUnits))
            $parameters['distanceUnit'] = $distanceUnit;

        try
        {
	        return $this->makeApiRequest('user/-/activities', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Failed logging activity.', 606, $e);
        }
    }

    /**
     * Delete user activity
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Validate the id
     *
     * @param string $id Activity log id
     * @throws FBException
     * @return bool
     */
    public function deleteActivity($id)
    {
	    try
	    {
            return $this->makeApiRequest('user/-/activities/' . $id, 'DELETE');
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Request to delete activity failed.', 607, $e);
	    }
    }

    /**
     * Add user favorite activity
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Validate the ID
     *
     * @param string $id Activity log id
     * @throws FBException
     * @return bool
     */
    public function addFavoriteActivity($id)
    {
        try
        {
	        return $this->makeApiRequest('user/-/activities/log/favorite/' . $id, 'POST');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to add favorite activity.', 608, $e);
        }
    }

    /**
     * Delete user favorite activity
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Validate the ID
     *
     * @param string $id Activity log id
     * @throws FBException
     * @return bool
     */
    public function deleteFavoriteActivity($id)
    {
        try
        {
	        return $this->makeApiRequest('user/-/activities/log/favorite/' . $id, 'DELETE');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to delete favorite activity.', 609, $e);
        }
    }

    /**
     * Get full description of specific activity
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Validate the ID
     *
     * @param  string $id Activity log Id
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getActivity($id)
    {
        try
        {
	        return $this->makeApiRequest('activities/' . $id);
        }
        catch (Exception $e)
        {
	        throw new FBException('Unable to get the requested activity.', 610, $e);
        }
    }

    /**
     * Get a tree of all valid FitBit public activities as well as private custom activities the user createds
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function browseActivities()
    {
        try
        {
	        return $this->makeApiRequest('activities');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to get a list of activities.', 611, $e);
        }
    }
}
