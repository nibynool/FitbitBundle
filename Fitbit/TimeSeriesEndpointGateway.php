<?php
/**
 *
 * Error Codes: 1301-1304
 */
namespace NibyNool\FitBitBundle\FitBit;

use NibyNool\FitBitBundle\FitBit\Exception as FBException;

/**
 * Class TimeSeriesEndpointGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class TimeSeriesEndpointGateway extends EndpointGateway
{
    /**
     * base fragment for the instantiated resource uri
     * 
     * @var string
     */
    protected static $format;

    /**
     * create a uri fragment from a method name
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Add validation for the method name
     * @todo Add validation for the fragment name
     *
     * @param string $method
     * @throws FBException
     * @return string
     */
    public function fragment($method)
    {
	    if (strlen($method) < 4) throw new FBException('Format or method not provided.', 1301);
        $method = substr($method, 3);
        $fragment = strtolower($method[0]) . substr($method, 1);
        return sprintf(static::$format, $fragment);
    }

    /**
     * Get user time series
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Can $period and $endDate be merged?
     *
     * @param  string $fragment
     * @param  \DateTime|string $baseDate
     * @param  string $period
     * @param  \DateTime|string $endDate
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function get($fragment, $baseDate = null, $period = null, $endDate = null)
    {
        $date1 = $baseDate ?: 'today';
        $date2 = ($period) ? $period : ($endDate) ?: '1d';

        if ($date1 instanceof \Datetime) $date1 = $date1->format("Y-m-d");
        if ($date2 instanceof \Datetime) $date2 = $date2->format("Y-m-d");

        $endpoint = sprintf('user/%s/%s/%s/%s', $this->userID, $fragment, $date1, $date2);

        try
        {
	        return $this->makeApiRequest($endpoint);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to complete API request ('.$fragment.')', 1302, $e);
        }
    }

    /**
     * Dynamically pass methods to get.
     *
     * @access public
     * @version 0.5.0
     *
     * @todo Check for function existance
     *
     * @param  string  $method
     * @param  array   $parameters
     * @throws FBException
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        try
        {
	        $fragment = $this->fragment($method);
        }
        catch (\Exception $e)
        {
	        throw new FBException('Unable to fragment method ('.$method.').', 1303, $e);
        }
        array_unshift($parameters, $fragment);
	    try
	    {
            return call_user_func_array(array($this, 'get'), $parameters);
	    }
	    catch (\Exception $e)
	    {
		    throw new FBException('Unable to perform get ('.$fragment.').', 1304, $e);
	    }
    }
}
