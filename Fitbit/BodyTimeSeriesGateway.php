<?php
namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class BodyTimeSeriesGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 * @version 0.1.1
 *
 * @method array getBmi(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getFat(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getWeight(\DateTime $baseDate, string $period, \DateTime $endDate)
 */
class BodyTimeSeriesGateway extends TimeSeriesEndpointGateway {

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
	 * @param  \DateTime $baseDate
	 * @param  string    $period
	 * @param  \DateTime $endDate
	 * @return array
	 */
	public function getWeightLogs(\DateTime $baseDate, $period, \DateTime $endDate)
	{
		return call_user_func_array(array($this, 'get'), array('log/weight/date', $baseDate, $period, $endDate));
	}

	/**
	 * Get the body fat logs for the selected date range.
	 *
	 * @access public
	 * @version 0.5.0
	 *
	 * @param  \DateTime $baseDate
	 * @param  string    $period
	 * @param  \DateTime $endDate
	 * @return array
	 */
	public function getFatLogs(\DateTime $baseDate, $period, \DateTime $endDate)
	{
		return call_user_func_array(array($this, 'get'), array('log/fat/date', $baseDate, $period, $endDate));
	}
}
