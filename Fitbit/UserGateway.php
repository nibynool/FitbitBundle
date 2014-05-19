<?php
/**
 *
 * Error Codes: 1601-1616
 */
namespace NibyNool\FitBitBundle\FitBit;

use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class UserGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class UserGateway extends EndpointGateway
{
    /**
     * API wrappers
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return object
     */
    public function getProfile()
    {
        try
        {
	        return $this->makeApiRequest('user/' . $this->userID . '/profile');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not get the profile.', 1601, $e);
        }
    }

    /**
     * Update user profile with array of parameters.
     *
     * @access public
     * @version 0.5.0
     *
     * @param array $parameters
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function updateProfileFromArray($parameters)
    {
        try
        {
	        return $this->makeApiRequest('user/' . $this->userID . '/profile', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not update the user profile.', 1602, $e);
        }
    }

    /**
     * Update user profile
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $gender 'FEMALE', 'MALE' or 'NA'
     * @param \DateTime $birthday Date of birth
     * @param string $height Height in cm/inches (as set with setMetric)
     * @param string $nickname Nickname
     * @param string $fullName Full name
     * @param string $timezone Timezone in the format 'America/Los_Angeles'
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function updateProfile($gender = null, \DateTime $birthday = null, $height = null, $nickname = null, $fullName = null, $timezone = null)
    {
        $parameters = array();
        if ($gender)   $parameters['gender'] = $gender;
        if ($birthday) $parameters['birthday'] = $birthday->format('Y-m-d');
        if ($height)   $parameters['height'] = $height;
        if ($nickname) $parameters['nickname'] = $nickname;
        if ($fullName) $parameters['fullName'] = $fullName;
        if ($timezone) $parameters['timezone'] = $timezone;

        try
        {
	        return $this->updateProfileFromArray($parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not update the user profile.', 1603, $e);
        }
    }

    /**
     * Get list of devices and their properties
     *
     * @access public
     * @version 0.5.0
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
	        throw new FBException('Could not get the device list.', 1604, $e);
        }
    }

    /**
     * Get user friends
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFriends()
    {
	    try
	    {
		    return $this->makeApiRequest('user/' . $this->userID . '/friends');
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Could not get the friends list.', 1605, $e);
	    }
    }

    /**
     * Get user's friends leaderboard
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function getFriendsLeaderboard()
    {
        try
        {
	        return $this->makeApiRequest('user/-/friends/leaderboard');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not get the friends leaderboard.', 1607, $e);
        }
    }

	/**
	 * Get friend invites
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @throws FBException
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getInvites()
	{
		try
		{
			return $this->makeApiRequest('user/-/friends/invitations');
		}
		catch (\Exception $e)
		{
			throw new FBException('Could not get friend invitations.', 1606, $e);
		}
	}

    /**
     * Invite user to become friends
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $userId Invite user by id
     * @param string $email Invite user by email address (could be already FitBit member or not)
     * @throws FBException
     * @return bool
     */
    public function inviteFriend($userId = null, $email = null)
    {
        $parameters = array();
        if (isset($userId)) $parameters['invitedUserId'] = $userId;
        if (isset($email)) $parameters['invitedUserEmail'] = $email;

        try
        {
	        return $this->makeApiRequest('user/-/friends/invitations', 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not invite the chosen friend', 1608, $e);
        }
    }

    /**
     * Accept invite to become friends from user
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $userId Id of the inviting user
     * @throws FBException
     * @return bool
     */
    public function acceptFriend($userId)
    {
        $parameters = array();
        $parameters['accept'] = 'true';

        try
        {
	        return $this->makeApiRequest('user/-/friends/invitations/' . $userId, 'POST', $parameters);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not accept friend invitation.', 1609, $e);
        }
    }

    /**
     * Reject invite to become friends from user
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $userId Id of the inviting user
     * @throws FBException
     * @return bool
     */
    public function rejectFriend($userId)
    {
        $parameters = array();
        $parameters['accept'] = 'false';

	    try
	    {
		    return $this->makeApiRequest('user/-/friends/invitations/' . $userId, 'POST', $parameters);
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Could not reject friend request.', 1610, $e);
	    }
    }

	/**
	 * Get badges
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @throws FBException
	 * @return mixed SimpleXMLElement or the value encoded in json as an object
	 */
	public function getBadges()
	{
		try
		{
			return $this->makeApiRequest('user/-/badges');
		}
		catch (\Exception $e)
		{
			throw new FBException('Could not get badges.', 1611, $e);
		}
	}

    /**
     * Add subscription
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $id Subscription ID
     * @param string $subscriptionType Collection type
     * @param string $subscriberId The ID of the subscriber
     * @throws FBException
     * @return mixed
     */
    public function addSubscription($id, $subscriptionType = 'all', $subscriberId = null)
    {
        try
        {
	        return $this->makeApiRequest(
		        $this->makeSubscriptionUrl($id, $subscriptionType),
		        'POST',
		        array(),
		        $this->makeSubscriptionHeaders($subscriberId)
	        );
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not add subscription.', 1612, $e);
        }
    }

    /**
     * Delete user subscription
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $id Subscription Id
     * @param string $subscriptionType Collection type
     * @param string $subscriberId The ID of the subscriber
     * @throws FBException
     * @return bool
     */
    public function deleteSubscription($id, $subscriptionType = 'all', $subscriberId = null)
    {
        try
        {
	        return $this->makeApiRequest(
		        $this->makeSubscriptionUrl($id, $subscriptionType),
		        'DELETE',
		        array(),
		        $this->makeSubscriptionHeaders($subscriberId)
	        );
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not delete subscription.', 1613, $e);
        }
    }

    /**
     * Validate user subscription type
     *
     * @access protected
     * @version 0.5.0
     *
     * @param string &$subscriptionType Collection type
     * @throws FBException
     * @return bool
     */
    protected function validateSubscriptionType(&$subscriptionType)
    {
	    if (!isset($this->configuration['subscription_types'][$subscriptionType])) throw new FBException('Invalid subscription type requested.', 1614);
	    $subscriptionType = $this->configuration['subscription_types'][$subscriptionType]['value'];
        return true;
    }

    /**
     * Create headers for subscription requests.
     *
     * @access protected
     *
     * @param string $subscriberId The ID of the subscriber
     * @return array
     */
    protected function makeSubscriptionHeaders($subscriberId = null)
    {
        $headers = array();
        if ($subscriberId) $headers['X-FitBit-Subscriber-Id'] = $subscriberId;
        return $headers;
    }

    /**
     * Create the subscription request URL
     *
     * @access protected
     * @version 0.5.0
     *
     * @param string $id Subscription Id
     * @param string $subscriptionType subscriptionType resource path
     * @throws FBException
     * @return string
     */
    protected function makeSubscriptionUrl($id, $subscriptionType)
    {
	    try
	    {
		    $this->validateSubscriptionType($subscriptionType);
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Invalid subscription type provided.', 1615, $e);
	    }
        return sprintf('user/%s%s/apiSubscriptions%s',
            $this->userID,
            $subscriptionType,
            ($id ? '/' . $id : '')
        );
    }

    /**
     * Get list of user's subscriptions for this application
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return mixed
     */
    public function getSubscriptions()
    {
        try
        {
	        return $this->makeApiRequest($this->makeSubscriptionUrl(null, null));
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to get subscriptions.', 1615, $e);
        }
    }
}
