<?php
namespace NibyNool\FitBitBundle\FitBit;

/**
 * Class RateLimiting
 *
 * @package NibyNool\FitBitBundle\FitBit
 *
 * @since 0.1.0
 */
class RateLimiting
{
	/**
	 * @var integer
	 */
	public $viewer;

	/**
	 * @var string
	 */
	public $viewerReset;
	/**
	 * @var integer
	 */
	public $viewerQuota;

	/**
	 * @var integer
	 */
	public $client;

	/**
	 * @var string
	 */
	public $clientReset;

	/**
	 * @var integer
	 */
	public $clientQuota;

	/**
	 * @todo Change reset values \DateTime
	 *
	 * @param integer $viewer
	 * @param integer $client
	 * @param string  $viewerReset
	 * @param string  $clientReset
	 * @param integer $viewerQuota
	 * @param integer $clientQuota
	 */
	public function __construct($viewer, $client, $viewerReset = null, $clientReset = null, $viewerQuota = null, $clientQuota = null)
    {
        $this->viewer = $viewer;
        $this->viewerReset = $viewerReset;
        $this->viewerQuota = $viewerQuota;
        $this->client = $client;
        $this->clientReset = $clientReset;
        $this->clientQuota = $clientQuota;
    }
}