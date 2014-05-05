<?php
/**
 * FitBitBundle v0.0.1
 *
 * Symfony Bundle for FitBit's OAuth-based REST API
 *
 * Forked from:
 * - https://github.com/jsamos/fitbitphp
 * - https://github.com/popthestack/fitbitphp
 * - https://github.com/TheSavior/fitbitphp
 * - https://github.com/heyitspavel/fitbitphp
 *
 * @author Michael Lambert <michael@alphageek.com.au>
 * @author jsamos
 * @author Ryan Martinsen
 * @author Eli White
 * @author heyitspavel
 */
namespace NibyNool\FitBitBundle\FitBit;

use OAuth\Common\Consumer\Credentials;
use OAuth\ServiceFactory;
use OAuth\OAuth1\Service\FitBit as ServiceInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Client\ClientInterface;
use NibyNool\FitBitBundle\FitBit\Exception as FBException;

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
     * @param string $format Response format (json or xml) to use in API calls
     * @throws FBException
     * @return self
     */
    public function setResponseFormat($format)
    {
        if (!in_array($format, array('json', 'xml'))) {
            throw new FBException("Response format must be one of 'json', 'xml'");
        }
        $this->responseFormat = $format;
        return $this;
    }

    /**
     * Set callback URL.
     * 
     * @access public
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
     * @param  ClientInterface $client
     * @return self
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;
        return $this;
    }

	/**
	 * Get the Authentication Gateway Interface
	 *
	 * @return AuthenticationGateway
	 */
	public function getAuthenticationGateway()
    {
        $gateway = new AuthenticationGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Activity Gateway Interface
	 *
	 * @return ActivityGateway
	 */
	public function getActivityGateway()
	{
		$gateway = new ActivityGateway;
		$this->injectGatewayDependencies($gateway);
		return $gateway;
	}

	/**
	 * Get the Activity Stats Gateway Interface
	 *
	 * @return ActivityStatsGateway
	 */
	public function getActivityStatsGateway()
	{
		$gateway = new ActivityStatsGateway;
		$this->injectGatewayDependencies($gateway);
		return $gateway;
	}

	/**
	 * Get the Activity Time Series Gateway Interface
	 *
	 * @return ActivityTimeSeriesGateway
	 */
	public function getActivityTimeSeriesGateway()
    {
        $gateway = new ActivityTimeSeriesGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Body Gateway Interface
	 *
	 * @return BodyGateway
	 */
	public function getBodyGateway()
    {
        $gateway = new BodyGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Body Time Series Gateway Interface
	 *
	 * @return BodyTimeSeriesGateway
	 */
	public function getBodyTimeSeriesGateway()
    {
        $gateway = new BodyTimeSeriesGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Food Gateway Interface
	 *
	 * @return FoodGateway
	 */
	public function getFoodGateway()
    {
        $gateway = new FoodGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Food Time Series Gateway Interface
	 *
	 * @return FoodTimeSeriesGateway
	 */
	public function getFoodTimeSeriesGateway()
    {
        $gateway = new FoodTimeSeriesGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Sleep Gateway Interface
	 *
	 * @return SleepGateway
	 */
	public function getSleepGateway()
    {
        $gateway = new SleepGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Sleep Time Series Gateway Interface
	 *
	 * @return SleepTimeSeriesGateway
	 */
	public function getSleepTimeSeriesGateway()
    {
        $gateway = new SleepTimeSeriesGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Time Gateway Interface
	 *
	 * @return TimeGateway
	 */
	public function getTimeGateway()
    {
        $gateway = new TimeGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Tracker Gateway Interface
	 *
	 * @return TrackerGateway
	 */
	public function getTrackerGateway()
	{
		$gateway = new TrackerGateway;
		$this->injectGatewayDependencies($gateway);
		return $gateway;
	}

	/**
	 * Get the User Gateway Interface
	 *
	 * @return UserGateway
	 */
	public function getUserGateway()
    {
        $gateway = new UserGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Get the Water Gateway Interface
	 *
	 * @return WaterGateway
	 */
	public function getWaterGateway()
    {
        $gateway = new WaterGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

	/**
	 * Inject Dependencies into a Gateway Interface
	 *
	 * @param EndpointGateway $gateway
	 */
	protected function injectGatewayDependencies(EndpointGateway $gateway)
    {
	    $gateway->setService($this->getService())
                ->setResponseFormat($this->responseFormat)
                ->setUserID($this->userID);
    }

    /**
     * Get FitBit service
     *
     * @access protected
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
