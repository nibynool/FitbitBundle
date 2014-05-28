<?php
/**
 *
 * Error Codes: 1001-1006
 */
namespace Nibynool\FitbitInterfaceBundle\Fitbit;

use Nibynool\FitbitInterfaceBundle\Fitbit\Exception as FBException;

/**
 * Class GoalGateway
 *
 * @package Nibynool\FitbitInterfaceBundle\Fitbit
 *
 * @since 0.5.0
 */
class GoalGateway extends EndpointGateway
{
    /**
     * Get weight goal
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getBodyWeightGoal()
    {
        try
        {
	        return $this->makeApiRequest('user/' . $this->userID . '/body/log/weight/goal');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to get weight goal.', 1001, $e);
        }
    }

	/**
	 * Get body fat goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @throws FBException
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getBodyFatGoal()
	{
		try
		{
			return $this->makeApiRequest('user/' . $this->userID . '/body/log/fat/goal');
		}
		catch (\Exception $e)
		{
			throw new FBException('Unable to get body fat goal.', 1002, $e);
		}
	}

	/**
	 * Get daily activity goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @throws FBException
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getActivityDailyGoal()
	{
		try
		{
			return $this->makeApiRequest('user/' . $this->userID . '/activities/goals/daily');
		}
		catch (\Exception $e)
		{
			throw new FBException('Unable to get daily activity goal.', 1003, $e);
		}
	}

	/**
	 * Get weekly activity goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @throws FBException
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getActivityWeeklyGoal()
	{
		try
		{
			return $this->makeApiRequest('user/' . $this->userID . '/activities/goals/weekly');
		}
		catch (\Exception $e)
		{
			throw new FBException('Unable to get weekly activity goal.', 1004, $e);
		}
	}

	/**
	 * Get food goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @throws FBException
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getFoodGoal()
	{
		try
		{
			return $this->makeApiRequest('user/' . $this->userID . '/foods/log/goal');
		}
		catch (\Exception $e)
		{
			throw new FBException('Unable to get food goal.', 1005, $e);
		}
	}

	/**
	 * Get water goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @throws FBException
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getWaterGoal()
	{
		try
		{
			return $this->makeApiRequest('user/' . $this->userID . '/foods/log/water/goal');
		}
		catch (\Exception $e)
		{
			throw new FBException('Unable to get water goal.', 1006, $e);
		}
	}
}
