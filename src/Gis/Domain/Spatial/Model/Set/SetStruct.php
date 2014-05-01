<?php
namespace Gis\Domain\Spatial\Model\Set;

use Gis\Core\DataProvider\IDataStruct;

/**
 * A camelCasse mapping of the sets table
 */
class SetStruct implements IDataStruct
{
	public $id;
	public $name;
	public $layer;
	public $type;
	public $minX;
	public $minY;
	public $maxX;
	public $maxY;
}
