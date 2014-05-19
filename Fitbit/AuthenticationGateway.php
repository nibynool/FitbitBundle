<?php
/**
 *
 * Error Codes: 201 - 206
 */
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
class AuthenticationGateway extends EndpointGateway
{
	/**
	 * Determine if this user is authorised with FitBit
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @throws FBException
	 * @return bool
	 */
	public function isAuthorized()
    {
        try
        {
	        return $this->service->getStorage()->hasAccessToken('FitBit');
        }
        catch (\Exception $e)
        {
	        throw new FBException('Could not find the access token.', 206, $e);
        }
    }

    /**
     * Initiate the login process
     *
     * @access public
     * @version 0.5.0
     *
     * @throws FBException
     * @return void
     */
    public function initiateLogin()
    {
	    /** @var TokenInterface $token */
        $token = $this->service->requestRequestToken();
        $url = $this->service->getAuthorizationUri(['oauth_token' => $token->getRequestToken()]);
	    if (!filter_var($url, FILTER_VALIDATE_URL)) throw new FBException('FitBit returned an invalid login URL ('.$url.').', 201);
	    header('Location: ' . $url);
        exit;
    }
    
    /**
     * Authenticate user, request access token.
     *
     * @access public
     * @version 0.5.0
     *
     * @param string $token
     * @param string $verifier
     * @throws FBException
     * @return TokenInterface
     */
    public function authenticateUser($token, $verifier)
    {
	    try
	    {
		    /** @var TokenInterface $tokenSecret */
	        $tokenSecret = $this->service->getStorage()->retrieveAccessToken('FitBit');
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Could not retrieve the access token secret.', 202, $e);
	    }

	    try
	    {
	        return $this->service->requestAccessToken(
                $token,
                $verifier,
                $tokenSecret->getRequestTokenSecret()
	        );
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Unable to request the access token.', 203, $e);
	    }
    }

    /**
     * Reset session
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Need to add clear to the interface for phpoauthlib (this item was here when this project was branched)
     *
     * @throws FBException
     * @return void
     */
    public function resetSession()
    {
	    try
	    {
		    $this->service->getStorage()->clearToken('FitBit');
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Could not clear the token.', 204);
	    }
    }

	/**
	 * Verify the token
	 *
	 * @access protected
	 * @version 0.5.0
	 *
	 * @throws Exception
	 * @return bool
	 */
	protected function verifyToken()
    {
        if (!$this->isAuthorized()) throw new FBException('Token could not be verified.', 205);
	    return true;
    }
}
