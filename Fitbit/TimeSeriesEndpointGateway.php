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
     * @param  string $fragment
     * @param  \DateTime|string $baseDate
     * @param  \DateTime|string $end
     * @throws FBException
     * @return mixed SimpleXMLElement or the value encoded in json as an object
     */
    public function get($fragment, $baseDate = null, $end = null)
    {
	    if (!isset($baseDate)) $date1 = 'today';
	    elseif ($baseDate instanceof \Datetime) $date1 = $baseDate->format('Y-m-d');
	    else $date1 = $baseDate;
	    if (!isset($end)) $date2 = '1d';
	    elseif ($end instanceof \Datetime) $date2 = $end->format('Y-m-d');
	    else $date2 = $end;

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
