<?php
namespace Gis\Domain\Spatial\Model\Attribute;

use Gis\Core\DataProvider\IDataStruct;

/**
 * A camelCase mapping of the attributes table
 */
class AttributeStruct implements IDataStruct
{
	public $id;
	public $setId;
	public $sequence;
	public $name;
	public $value;
	
}
