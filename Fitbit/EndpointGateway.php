<?php

namespace NibyNool\FitBitBundle\FitBit;

use OAuth\OAuth1\Service\FitBit as ServiceInterface;

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
     * @param string $resource Endpoint after '.../1/'
     * @param string $method ('GET', 'POST', 'PUT', 'DELETE')
     * @param array $body Request parameters
     * @param array $extraHeaders Additional custom headers
     * @return mixed stdClass for json response, SimpleXMLElement for XML response.
     */
    protected function makeApiRequest($resource, $method = 'GET', $body = array(), $extraHeaders = array())
    {
        $path = $resource . '.' . $this->responseFormat;

        if ($method == 'GET' && $body) {
            $path .= '?' . http_build_query($body);
            $body = array();
        }

        $response = $this->service->request($path, $method, $body, $extraHeaders);

        return $this->parseResponse($response);
    }

    /**
     * Parse json or XML response.
     *
     * @access private
     * @param string $response
     * @return mixed stdClass for json response, SimpleXMLElement for XML response.
     */
    private function parseResponse($response)
    {
        if ($this->responseFormat == 'json')    return json_decode($response);
        elseif ($this->responseFormat == 'xml') return simplexml_load_string($response);

        return $response;
    }

    /**
     * Get CLIENT+VIEWER and CLIENT rate limiting quota status
     *
     * @access public
     * @return RateLimiting
     */
    public function getRateLimit()
    {
        $clientAndUser = $this->makeApiRequest('account/clientAndViewerRateLimitStatus');
        $client        = $this->makeApiRequest('account/clientRateLimitStatus');

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
