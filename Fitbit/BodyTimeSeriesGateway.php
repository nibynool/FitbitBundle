<?php
/**
 *
 * Error Codes: 9XX
 */
namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class BodyTimeSeriesGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 * @version 0.1.1
 *
 * @method array getBmi(\DateTime $baseDate, \DateTime $endDate)
 * @method array getFat(\DateTime $baseDate, \DateTime $endDate)
 * @method array getWeight(\DateTime $baseDate, \DateTime $endDate)
 */
class BodyTimeSeriesGateway extends TimeSeriesEndpointGateway
{
    /**
     * Base fragment for this resources uri
     *
     * @version 0.1.1
     * @var string
     */
    protected static $format = 'body/%s/date';

	/**
	 * Get the weight/bmi logs for the selected date range.
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @param  \DateTime|string $baseDate
	 * @param  \DateTime|string $endDate
	 * @return array
	 */
	public function getWeightLogs($baseDate, $endDate)
	{
		return call_user_func_array(array($this, 'get'), array('log/weight/date', $baseDate, $endDate));
	}

	/**
	 * Get the body fat logs for the selected date range.
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @param  \DateTime|string $baseDate
	 * @param  \DateTime|string $endDate
	 * @return array
	 */
	public function getFatLogs(\DateTime $baseDate, \DateTime $endDate)
	{
		return call_user_func_array(array($this, 'get'), array('log/fat/date', $baseDate, $endDate));
	}
}
