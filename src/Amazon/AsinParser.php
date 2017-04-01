<?php
namespace Amazon;

use Amazon\Exceptions\InvalidAsinException;


/**
 * Class AsinFetcher
 * @see https://de.wikipedia.org/wiki/Amazon_Standard_Identification_Number
 * @package Amazon
 */
class AsinParser extends Parser
{

	const LENGTH_ASIN = 10;

	/**
	 * @var string
	 */
	private $asin = null;

	public function __construct($url)
	{
		parent::__construct($url);
		$urlParameter = parse_url($this->getUrl());

		$this->processAsin($urlParameter['host'], $urlParameter['path']);
	}

	protected function processAsin($host, $path)
	{
		preg_match("/B[\dA-Z]{9}|\d{9}(X|\d)/", $path, $matchs);
		if  (count($matchs) <= 0) {
			throw new InvalidAsinException(sprintf('Url %s has no valid ASIN', $this->getUrl()));
		}
		$this->setAsin($matchs[0]);
		return;
	}

	/**
	 * @return string
	 */
	public function getAsin()
	{
		return $this->asin;
	}

	/**
	 * @param string $asin
	 */
	protected function setAsin($asin)
	{
		$this->asin = $asin;
	}

	/**
	 * @return string
	 */
	public function getCleanedUrl()
	{
		return sprintf('http://www.amazon.%s/dp/%s/', $this->getTld(), $this->getAsin());
	}
}
