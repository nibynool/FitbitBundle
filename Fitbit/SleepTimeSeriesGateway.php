<?php
/**
 *
 * Error Codes: 20XX
 */
namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class SleepTimeSeriesGateway
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 * @version 0.1.1
 *
 * @method array getStartTime(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getTimeInBed(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getMinutesAsleep(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getAwakeningsCount(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getMinutesAwake(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getMinutesToFallAsleep(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getMinutesAfterWakeup(\DateTime $baseDate, string $period, \DateTime $endDate)
 * @method array getEfficiency(\DateTime $baseDate, string $period, \DateTime $endDate)
 */
class SleepTimeSeriesGateway extends TimeSeriesEndpointGateway {
    /**
     * base fragment for this resources uri
     * 
     * @var string
     */
    protected static $format = 'sleep/%s/date';
}
