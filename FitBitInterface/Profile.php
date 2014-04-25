<?php

namespace NibyNool\FitBitInterfaceBundle\FitBitInterface;

use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

use NibyNool\FitBitBundle\FitBit\TokenStorage;
use NibyNool\FitBitBundle\FitBit\ApiGatewayFactory as FitBitFactory;
use NibyNool\FitBitBundle\FitBit\UserGateway;
use NibyNool\FitBitBundle\FitBit\RateLimiting;

use NibyNool\FitBitStorageBundle\Entity\FitBitUser;
use NibyNool\FitBitStorageBundle\Entity\FitBitProfile;
use NibyNool\FitBitStorageBundle\Entity\FitBitRequestLog;

class Profile extends ContainerAware
{
	/** @var null|FitBitUser The Current FitBit User ID */
	protected $user = null;
	/** @var string[] $fields Array of field names to match */
	protected $fields = array(
		'aboutMe', 'avatar', 'avatar150',
		'city', 'country',
		'dateOfBirth', 'displayName', 'distanceUnit',
		'encodedId',
		'foodsLocale', 'fullName',
		'gender', 'glucoseUnit',
		'height', 'heightUnit',
		'nickname',
		'locale',
		'memberSince',
		'offsetFromUTCMillis',
		'state', 'strideLengthRunning', 'strideLengthWalking',
		'timezone',
		'waterUnit', 'weightUnit'
	);

	/**
	 * Set the FitBit User record we are working with.
	 *
	 * It is assumed that you have an external method to connect your local user to a FitBit User
	 *
	 * @param FitBitUser $user The FitBitUser object
	 *
	 * @return self
	 */
	public function setUser(FitBitUser $user)
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * Get the latest profile record for this user.  Update it if required.
	 *
	 * @return FitBitProfile|null
	 */
	public function getLatestProfile()
	{
		/** @var EntityRepository $profileRepo */
		$profileRepo = $this->container->get('doctrine')->getRepository('NibyNoolFitBitStorageBundle:FitBitProfile');
		/** @var FitBitProfile $profileResult */
		$profileResult = $profileRepo->findOneBy(
			array(
				'FitBitUser' => $this->user
			),
			array(
				'recordTimestamp' => 'DESC'
			)
		);
		if (is_null($profileResult)) return $this->updateFitBitProfile();

		/** @var \DateTime $latestUpdateRequiredTime */
		$latestUpdateRequiredTime = new \DateTime();
		$latestUpdateRequiredTime->modify('-1 hour');
		if ($profileResult->getRecordTimestamp() < $latestUpdateRequiredTime)
		{
			/** @var EntityRepository $requestLogRepo */
			$requestLogRepo = $this->container->get('doctrine')->getRepository('NibyNoolFitBitStorageBundle:FitBitRequestLog');
			/** @var FitBitRequestLog $requestLogResult */
			$requestLogResult = $requestLogRepo->findOneBy(
				array(
					'FitBitUser' => $this->user,
					'endPoint'   => 'user'
				),
				array(
					'requestTimestamp' => 'DESC'
				)
			);
			if (is_null($requestLogResult)) return $this->updateFitBitProfileIfRequired();
			if ($requestLogResult->getRequestTimestamp() < $latestUpdateRequiredTime) return $this->updateFitBitProfileIfRequired();
		}
		return $profileResult;
	}

	/**
	 * Update the current user's FitBit profile if it differs from the one we have recorded
	 * @return FitBitProfile
	 */
	private function updateFitBitProfileIfRequired()
	{
		/** @var EntityRepository $profileRepo */
		$profileRepo = $this->container->get('doctrine')->getRepository('NibyNoolFitBitStorageBundle:FitBitProfile');
		/** @var FitBitProfile $profileResult */
		$profileResult = $profileRepo->findOneBy(
			array(
				'FitBitUser' => $this->user
			),
			array(
				'recordTimestamp' => 'DESC'
			)
		);
		if (is_null($profileResult)) return $this->updateFitBitProfile();
		/** @var FitBitFactory $fitbit */
		$fitbit = $this->connectToFitBit();
		/** @var UserGateway $fitbitUser */
		$fitbitUser = $fitbit->getUserGateway();
		/** @var RateLimiting $rateLimitStatus */
		$rateLimitStatus = $fitbitUser->getRateLimit();
		// TODO: Check the rate limit before continuing
		/** @var object $fitbitUserProfile */
		$fitbitUserProfile = $fitbitUser->getProfile();
		if (!$this->profilesMatch($fitbitUserProfile, $profileResult)) return $this->updateFitBitProfile($fitbitUserProfile);
		return $profileResult;
	}

	/**
	 * Perform the actual update to a fitbit profile record
	 *
	 * @param object|null $fitbitUserProfile
	 *
	 * @return FitBitProfile
	 */
	private function updateFitBitProfile($fitbitUserProfile = null)
	{
		if (is_null($fitbitUserProfile))
		{
			/** @var FitBitFactory $fitbit */
			$fitbit = $this->connectToFitBit();
			/** @var UserGateway $fitbitUser */
			$fitbitUser = $fitbit->getUserGateway();
			/** @var RateLimiting $rateLimitStatus */
			$rateLimitStatus = $fitbitUser->getRateLimit();
			// TODO: Check the rate limit before continuing
			/** @var object $fitbitUserProfile */
			$fitbitUserProfile = $fitbitUser->getProfile();
		}
		/** @var FitBitProfile $profile */
		$profile = new FitBitProfile();
		$profile->setFitBitUser($this->user);
		$profile->setRecordTimestamp(new \DateTime());
		foreach ($this->fields as $field)
		{
			$profile->{'set'.ucfirst($field)}($fitbitUserProfile->{'get'.ucfirst($field)}());
		}
		/** @var EntityManager $entityManager */
		$entityManager = $this->container->get('doctrine')->getManager();
		$entityManager->persist($profile);
		$entityManager->flush();
		return $profile;
	}

	/**
	 * Return true if the fitbit and database profiles match, false otherwise
	 *
	 * @param object $fitbitProfile
	 * @param FitBitProfile $databaseProfile
	 *
	 * @return bool
	 */
	private function profilesMatch($fitbitProfile, $databaseProfile)
	{
		/** @var boolean $match True if the profiles match, false if they differ */
		$match = true;
		/** @var string $field */
		foreach ($this->fields as $field)
		{
			if ($fitbitProfile->{'get'.ucfirst($field)}() != $databaseProfile->{'get'.ucfirst($field)}()) $match = false;
		}
		return $match;
	}

	/**
	 * Connect to FitBit as the current user
	 *
	 * @return FitBitFactory
	 */
	private function connectToFitBit()
	{
		/** @var FitBitFactory $fitbit **/
		$fitbit = $this->container->get('fitbit');
		/** @var TokenStorage $tokenStorage */
		$tokenStorage = new TokenStorage($this->user->getOAuthToken(), $this->user->getOAuthSecret());
		$fitbit->setStorageAdapter($tokenStorage->getAdapter());
		return $fitbit;
	}
}