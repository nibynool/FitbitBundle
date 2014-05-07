<?php
namespace NibyNool\FitBitBundle\FitBit;

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
     * @todo Handle failed API requests gracefully
     *
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getBodyWeightGoal()
    {
        return $this->makeApiRequest('user/' . $this->userID . '/body/log/weight/goal');
    }

	/**
	 * Get body fat goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @todo Handle failed API requests gracefully
	 *
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getBodyFatGoal()
	{
		return $this->makeApiRequest('user/' . $this->userID . '/body/log/fat/goal');
	}

	/**
	 * Get daily activity goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @todo Handle failed API requests gracefully
	 *
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getActivityDailyGoal()
	{
		return $this->makeApiRequest('user/' . $this->userID . '/activities/goals/daily');
	}

	/**
	 * Get weekly activity goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @todo Handle failed API requests gracefully
	 *
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getActivityWeeklyGoal()
	{
		return $this->makeApiRequest('user/' . $this->userID . '/activities/goals/weekly');
	}

	/**
	 * Get food goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @todo Handle failed API requests gracefully
	 *
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getFoodGoal()
	{
		return $this->makeApiRequest('user/' . $this->userID . '/foods/log/goal');
	}

	/**
	 * Get water goal
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @todo Handle failed API requests gracefully
	 *
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getWaterGoal()
	{
		return $this->makeApiRequest('user/' . $this->userID . '/foods/log/water/goal');
	}
}
