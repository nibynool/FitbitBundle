<?php
namespace NibyNool\FitBitBundle\FitBit;

use OAuth\Common\Consumer\Credentials;
use OAuth\ServiceFactory;
use OAuth\OAuth1\Service\FitBit as ServiceInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Client\ClientInterface;
use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class ApiGatewayFactory
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 * @version 0.5.0
 *
 * @method AuthenticationGateway getAuthenticationGateway()
 * @method ActivityGateway getActivityGateway()
 * @method ActivityStatsGateway getActivityStatsGateway()
 * @method ActivityTimeSeriesGateway getActivityTimeSeriesGateway()
 * @method BodyGateway getBodyGateway()
 * @method BodyTimeSeriesGateway getBodyTimeSeriesGateway()
 * @method FoodGateway getFoodGateway()
 * @method FoodTimeSeriesGateway getFoodTimeSeriesGateway()
 * @method GoalGateway getGoalGateway()
 * @method SleepGateway getSleepGateway()
 * @method SleepTimeSeriesGateway getSleepTimeSeriesGateway()
 * @method TimeGateway getTimeGateway()
 * @method TrackerGateway getTrackerGateway()
 * @method UserGateway getUserGateway()
 * @method WaterGateway getWaterGateway()
 */
class ApiGatewayFactory
{
    /**
     * @var string
     */
    protected $consumerKey;

    /**
     * @var string
     */
    protected $consumerSecret;

    /**
     * @var string (default: '-')
     */
    protected $userID = '-';
    
    /**
     * @var string (default: 'json')
     */
    protected $responseFormat = 'json';

    /**
     * @var ServiceInterface
     */
    protected $service;
    
    /**
     * @var TokenStorageInterface
     */
    protected $storageAdapter;
    
    /**
     * @var string
     */
    protected $callbackURL;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

	/**
	 * Set the consumer credentials when this class is instantiated
	 *
	 * @access public
	 *
	 * @param string $consumer_key Application consumer key for FitBit API
	 * @param string $consumer_secret Application secret
	 * @param string $callback_url Callback URL to provide to FitBit
	 */
	public function __construct($consumer_key, $consumer_secret, $callback_url)
	{
		$this->consumerKey    = $consumer_key;
		$this->consumerSecret = $consumer_secret;
		$this->callbackURL    = $callback_url;
	}

	/**
     * Set consumer credentials
     * 
     * @access public
	 *
     * @param string $consumer_key Application consumer key for FitBit API
     * @param string $consumer_secret Application secret
     * @return self
     */
    public function setCredentials($consumer_key, $consumer_secret)
    {
        $this->consumerKey    = $consumer_key;
        $this->consumerSecret = $consumer_secret;
        return $this;
    }

    /**
     * Set storage adapter.
     * 
     * @access public
     *
     * @param TokenStorageInterface $adapter
     * @return self
     */
    public function setStorageAdapter(TokenStorageInterface $adapter)
    {
        $this->storageAdapter = $adapter;
        return $this;
    }

    /**
     * Get storage adapter.
     * 
     * @access public
     *
     * @return TokenStorageInterface
     */
    public function getStorageAdapter()
    {
        return $this->storageAdapter;
    }

    /**
     * Set response format.
     *
     * @access public
     * @version 0.1.1
     *
     * @param string $format Response format (json or xml) to use in API calls
     * @throws FBException
     * @return self
     */
    public function setResponseFormat($format)
    {
        if (!in_array($format, array('json', 'xml'))) throw new FBException("Response format must be one of 'json', 'xml'");
        $this->responseFormat = $format;
        return $this;
    }

    /**
     * Set callback URL.
     * 
     * @access public
     *
     * @todo Add URL validation
     * @todo Allow URL to be relative to the root of the site
     *
     * @param string $url
     * @return self
     */
    public function setCallbackURL($url)
    {
        $this->callbackURL = $url;
        return $this;
    }

    /**
     * Set FitBit user id for API calls
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
     * Set HTTP Client library for FitBit service.
     *
     * @access public
     *
     * @param  ClientInterface $client
     * @return self
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;
        return $this;
    }

	/**
	 * Open a Gateway
	 *
	 * @access public
	 *
	 * @param $method
	 * @param $parameters
	 * @throws Exception
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if (!preg_match('/^get.*Gateway$/', $method)) throw new FBException("Invalid function requested.");
		if (count($parameters)) throw new FBException("Gateway interfaces do not accept parameters.");
		$gatewayName = substr($method, 3);
		try
		{
			$gateway = new $gatewayName;
			$this->injectGatewayDependencies($gateway);
		}
		catch (\Exception $e)
		{
			throw new FBException("Could not open a gateway named ".$gatewayName);
		}
		return $gateway;
	}

	/**
	 * Inject Dependencies into a Gateway Interface
	 *
	 * @access protected
	 *
	 * @param EndpointGateway $gateway
	 * @return bool
	 */
	protected function injectGatewayDependencies(EndpointGateway $gateway)
    {
	    $gateway->setService($this->getService())
                ->setResponseFormat($this->responseFormat)
                ->setUserID($this->userID);
	    return true;
    }

    /**
     * Get FitBit service
     *
     * @access protected
     *
     * @throws FBException
     * @return ServiceInterface
     */
    protected function getService()
    {
        if (!$this->consumerKey)    throw new FBException('Empty consumer key.');
        if (!$this->consumerSecret) throw new FBException('Empty consumer secret.');
        if (!$this->callbackURL)    throw new FBException('Empty callback URL.');
        if (!$this->storageAdapter) throw new FBException('Missing storage adapter.');

        if (!$this->service)
        {
            $credentials = new Credentials(
                $this->consumerKey,
                $this->consumerSecret,
                $this->callbackURL
            );

            $factory = new ServiceFactory();

            if ($this->httpClient) $factory->setHttpClient($this->httpClient);

            $this->service = $factory->createService('FitBit', $credentials, $this->storageAdapter);
        }

        return $this->service;
    }
}
