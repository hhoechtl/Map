<?php
namespace Ttree\Map\Eel\Helper;

use TYPO3\Eel\ProtectedContextAwareInterface;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * GeoPoint EEL Helper
 *
 * @Flow\Proxy(false)
 */
class GeoPointHelper implements ProtectedContextAwareInterface {

	/**
	 * Generate a valid ES Geo Point
	 *
	 * @param NodeInterface $node
	 * @return array
	 * @see https://www.elastic.co/guide/en/elasticsearch/guide/current/lat-lon-formats.html
	 */
	public function extract(NodeInterface $node) {
		$latitude = $node->getProperty('latitude') ?: $node->getProperty('reversedLatitude');
		$longitude = $node->getProperty('longitude') ?: $node->getProperty('reversedLongitude');
		if (empty($latitude) || empty($longitude)) {
			return NULL;
		}
		return [
			'lat' => $latitude,
			'lon' => $longitude,
		];
	}

	/**
	 * All methods are considered safe
	 *
	 * @param string $methodName
	 * @return boolean
	 */
	public function allowsCallOfMethod($methodName) {
		return TRUE;
	}

}