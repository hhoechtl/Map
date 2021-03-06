<?php
namespace Ttree\Map\TypeConverter;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Property\Exception\InvalidPropertyMappingConfigurationException;
use TYPO3\Flow\Property\PropertyMappingConfigurationInterface;
use TYPO3\Flow\Property\TypeConverter\AbstractTypeConverter;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use Ttree\Map\Domain\Model\Address;

/**
 * Address Type Converter
 *
 * This type converter is used to convert a Node to an address string usable for reverse geocoding
 *
 * @api
 */
class AddressConverter extends AbstractTypeConverter {

	/**
	 * @var array<string>
	 */
	protected $sourceTypes = array(NodeInterface::class);

	/**
	 * @var string
	 */
	protected $targetType = Address::class;

	/**
	 * @var integer
	 */
	protected $priority = 1;

	/**
	 * Actually convert from $source to $targetType, by doing a typecast.
	 *
	 * @param mixed $source
	 * @param string $targetType
	 * @param array $convertedChildProperties
	 * @param PropertyMappingConfigurationInterface $configuration
	 * @return Address|\TYPO3\Flow\Error\Error
	 * @throws InvalidPropertyMappingConfigurationException
	 * @api
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), PropertyMappingConfigurationInterface $configuration = NULL) {
		$configuration = $this->getConfigurationKeysAndValues($configuration, array('streetAddressPropertyName', 'postalCodePropertyName', 'addressLocalityPropertyName'));

		/** @var NodeInterface $node */
		$node = $source;
		$streetAddressPropertyName = is_string($configuration['streetAddressPropertyName']) ? $configuration['streetAddressPropertyName'] : 'streetAddress';
		if (!$node->hasProperty($streetAddressPropertyName)) {
			throw new InvalidPropertyMappingConfigurationException(sprintf('Missing property "%s"', $streetAddressPropertyName), 1436265746);
		}
		$postalCodePropertyName = is_string($configuration['postalCodePropertyName']) ? $configuration['postalCodePropertyName'] : 'postalCode';
		if (!$node->hasProperty($postalCodePropertyName)) {
			throw new InvalidPropertyMappingConfigurationException(sprintf('Missing property "%s"', $postalCodePropertyName), 1436265747);
		}
		$addressLocalityPropertyName = is_string($configuration['addressLocalityPropertyName']) ? $configuration['addressLocalityPropertyName'] : 'addressLocality';
		if (!$node->hasProperty($addressLocalityPropertyName)) {
			throw new InvalidPropertyMappingConfigurationException(sprintf('Missing property "%s"', $addressLocalityPropertyName), 1436265748);
		}

		return new Address($node->getProperty($streetAddressPropertyName), $node->getProperty($postalCodePropertyName), $node->getProperty($addressLocalityPropertyName));
	}

	/**
	 * Helper method to collect configuration for this class.
	 *
	 * @param PropertyMappingConfigurationInterface $configuration
	 * @param array $configurationKeys
	 * @return array
	 */
	protected function getConfigurationKeysAndValues(PropertyMappingConfigurationInterface $configuration, array $configurationKeys) {
		$keysAndValues = array();
		foreach ($configurationKeys as $configurationKey) {
			$keysAndValues[$configurationKey] = $configuration->getConfigurationValue(self::class, $configurationKey);
		}
		return $keysAndValues;
	}

}