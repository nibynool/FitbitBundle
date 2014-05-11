<?php
namespace NibyNool\FitBitBundle\FitBit;

use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class GoalGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.5.0
 */
class GoalGateway extends EndpointGateway {

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
	        throw new FBException($e->getMessage());
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
			throw new FBException($e->getMessage());
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
			throw new FBException($e->getMessage());
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
			throw new FBException($e->getMessage());
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
			throw new FBException($e->getMessage());
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
			throw new FBException($e->getMessage());
		}
	}
}
