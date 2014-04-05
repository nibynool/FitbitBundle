<?php

namespace NibyNool\FitBitBundle\FitBit;

use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\Common\Storage\Memory;

class TokenStorage
{
	/** @var StdOAuth1Token */
	protected $token;
	/** @var Memory */
	protected $adapter;

	/**
	 * Constructor for the token storage
	 *
	 * @param string|null $token  The token to be added to the storage if this is pre-authorised
	 * @param string|null $secret The secret associated with the token.
	 */
	public function __construct($token = null, $secret = null)
	{
		$this->token = new StdOAuth1Token();
		if ($token !== null && $secret !== null)
		{
			$this->token->setRequestToken($token);
			$this->token->setRequestTokenSecret($secret);
			$this->token->setAccessToken($token);
			$this->token->setAccessTokenSecret($secret);
		}

		$this->adapter = new Memory();
		$this->adapter->storeAccessToken('FitBit', $token);
	}

	/**
	 * Get the storage adapter
	 *
	 * @return Memory
	 */
	public function getAdapter()
	{
		return $this->adapter;
	}
}