<?php
namespace Gis\Domain\Spatial\Model\Attribute;

use Gis\Core\Connection\GisConnectionFactory;
use Gis\Core\Domain\BaseRetrievableDomainObjectFactory;
use Gis\Core\DataProvider\IDataStruct;

/**
 *  AttributeFactory
 *  Create instances of Attribute by injecting
 *  in all of its dependencies.
 */
class AttributeFactory extends BaseRetrievableDomainObjectFactory implements IAttributeFactory
{
	/**
	 * Used to construct the DataProvider needed by this factory
	 */
	public function __construct()
	{
		$dataProvider = new AttributeDataProvider(new GisConnectionFactory());
		parent::__construct($dataProvider);
	}

	/**
	 * Will create a constructed instance of an Attribute from
     * a AttributeStruct
	 * @param	IDataStruct $struct
	 * @return	Attribute
	 */
	public function create(IDataStruct $struct = null)
	{
		$attribute = new Attribute($this->getDomainDataProvider());
		$struct = is_null($struct) ? new AttributeStruct() : $struct;
		$attribute->setDataStruct($struct);
		return $attribute;
	}
}
