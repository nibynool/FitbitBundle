<?php
namespace NibyNool\FitBitBundle\FitBit;

use OAuth\OAuth1\Token\TokenInterface;
use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class AuthenticationGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class AuthenticationGateway extends EndpointGateway {

	/**
	 * Determine if this user is authorised with FitBit
	 *
	 * @access public
	 *
	 * @return bool
	 */
	public function isAuthorized()
    {
        return $this->service->getStorage()->hasAccessToken('FitBit');
    }

    /**
     * Initiate the login process
     *
     * @access public
     *
     * @throws FBException
     * @return void
     */
    public function initiateLogin()
    {
	    /** @var TokenInterface $token */
        $token = $this->service->requestRequestToken();
        $url = $this->service->getAuthorizationUri(['oauth_token' => $token->getRequestToken()]);
	    if (!filter_var($url, FILTER_VALIDATE_URL)) throw new FBException($url." is not a valid URL despite being returned from FitBit");
	    header('Location: ' . $url);
        exit;
    }
    
    /**
     * Authenticate user, request access token.
     *
     * @access public
     *
     * @param string $token
     * @param string $verifier
     * @return TokenInterface
     */
    public function authenticateUser($token, $verifier)
    {
	    /** @var TokenInterface $tokenSecret */
        $tokenSecret = $this->service->getStorage()->retrieveAccessToken('FitBit');
        
        return $this->service->requestAccessToken(
            $token,
            $verifier,
            $tokenSecret->getRequestTokenSecret()
        );
    }

    /**
     * Reset session
     *
     * @access public
     *
     * @todo Need to add clear to the interface for phpoauthlib (this item was here when this project was branched)
     *
     * @return void
     */
    public function resetSession()
    {
        $this->service->getStorage()->clearToken('FitBit');
    }

	/**
	 * @access protected
	 *
	 * @throws Exception
	 * @return bool
	 */
	protected function verifyToken()
    {
        if (!$this->isAuthorized()) throw new FBException("You must be authorized to make requests");
	    return true;
    }
}
