<?php
namespace NibyNool\FitBitBundle\FitBit;

use OAuth\OAuth1\Service\FitBit as ServiceInterface;
use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class EndpointGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class EndpointGateway {

    /**
     * @var ServiceInterface
     */
    protected $service;

    /**
     * @var string
     */
    protected $responseFormat;

    /**
     * @var string
     */
    protected $userID;

    /**
     * Set FitBit service
     *
     * @access public
     *
     * @param ServiceInterface $service
     * @return self
     */
    public function setService(ServiceInterface $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * Set response format.
     * 
     * @access public
     *
     * @param string $format
     * @return self
     */
    public function setResponseFormat($format)
    {
        $this->responseFormat = $format;
        return $this;
    }

    /**
     * Set FitBit user ids.
     *
     * @access public
     *
     * @param string $id
     * @return self
     */
    public function setUserID($id)
    {
        $this->userID = $id;
        return $this;
    }

    /**
     * Make an API request
     *
     * @access protected
     *
     * @param string $resource Endpoint after '.../1/'
     * @param string $method ('GET', 'POST', 'PUT', 'DELETE')
     * @param array $body Request parameters
     * @param array $extraHeaders Additional custom headers
     * @throws FBException
     * @return mixed stdClass for json response, SimpleXMLElement for XML response.
     */
    protected function makeApiRequest($resource, $method = 'GET', $body = array(), $extraHeaders = array())
    {
        $path = $resource . '.' . $this->responseFormat;

        if ($method == 'GET' && $body) {
            $path .= '?' . http_build_query($body);
            $body = array();
        }

	    try
	    {
            $response = $this->service->request($path, $method, $body, $extraHeaders);
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException($e->getMessage());
	    }

        try
        {
	        $response = $this->parseResponse($response);
        }
        catch (\Exception $e)
	    {
		    throw new FBException($e->getMessage());
	    }
	    return $response;
    }

    /**
     * Parse json or XML response.
     *
     * @access private
     *
     * @param string $response
     * @throws FBException
     * @return mixed stdClass for json response, SimpleXMLElement for XML response.
     */
    private function parseResponse($response)
    {
        if ($this->responseFormat == 'json')
        {
	        try
	        {
		        $response = json_decode($response);
	        }
	        catch (\Exception $e)
	        {
		        throw new FBException('Could not decode JSON response.');
	        }
        }
        elseif ($this->responseFormat == 'xml')
        {
	        try
	        {
		        $response = simplexml_load_string($response);
	        }
	        catch (\Exception $e)
	        {
		        throw new FBException('Could not decode XML response.');
	        }
        }
		else throw new FBException('Could not handle a response format of '.$this->responseFormat);
	    return $response;
    }

    /**
     * Get CLIENT+VIEWER and CLIENT rate limiting quota status
     *
     * @access public
     *
     * @todo Convert reset times to \DateTime
     *
     * @throws FBException
     * @return RateLimiting
     */
    public function getRateLimit()
    {
	    try
	    {
            $clientAndUser = $this->makeApiRequest('account/clientAndViewerRateLimitStatus');
            $client        = $this->makeApiRequest('account/clientRateLimitStatus');
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Could not get the rate limit data ('.$e->getMessage().')');
	    }

        return new RateLimiting(
            $clientAndUser->rateLimitStatus->remainingHits,
            $client->rateLimitStatus->remainingHits,
            $clientAndUser->rateLimitStatus->resetTime,
            $client->rateLimitStatus->resetTime,
            $clientAndUser->rateLimitStatus->hourlyLimit,
            $client->rateLimitStatus->hourlyLimit
        );
    }
}
